<?php

namespace App\Http\Controllers;

use App\Mail\infoMail;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmation;
use App\Mail\ReservationAnnulee;
use PhpParser\Node\Stmt\If_;

class FirstController extends Controller
{

    public function index()
    {
        $forms = Formation::where('status', 'publiee')
        ->withCount('inscriptions')
        ->orderByDesc('inscriptions_count')
        ->take(3)
        ->get();

        return view('index', compact('forms'));
    }

    public function redirect()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $usertype = Auth::user()->usertype;

        switch ($usertype) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'user':
                return redirect()->route('index');
            default:
                Auth::logout();
                return redirect('/')->with('error', 'Type d’utilisateur inconnu. Déconnexion forcée.');
        }
    }

    public function uLogout(Request $req){
        if(Auth::id()){
            Auth::logout();
            $req->session()->invalidate();
            $req->session()->regenerateToken();
            $forms = Formation::all();
            return redirect('/');
        }else{
            $forms = Formation::where('status', 'publiee')->get();
            return view('index', compact('forms'));
        }
    }

    public function formListing() {
        $forms = Formation::where('status', 'publiee')->get();
        return view('listing', compact('forms'));
    }


    public function formSingle($id) {
        $oneForm = Formation::find($id);

        if (!$oneForm || $oneForm->status !== 'publiee') {
            return redirect()->route('allForm')->with('warning', 'Cette formation n\'est pas disponible.');
        }

        $badgeColors = [
            'developpement' => 'bg-primary',
            'bureautique'   => 'bg-secondary',
            'gestion'       => 'bg-success',
            'langues'       => 'bg-warning text-dark',
            'marketing'     => 'bg-info text-dark',
            'design'        => 'bg-purple text-white',
            'informatique'  => 'bg-indigo text-white',
            'finance'       => 'bg-teal text-white',
        ];

        // Ajout de la classe de badge à la formation principale
        $oneForm->badgeClass = $badgeColors[$oneForm->categorie] ?? 'bg-secondary';

        $similarForms = Formation::where('categorie', $oneForm->categorie)
            ->where('id', '!=', $oneForm->id)
            ->where('status', 'publiee')
            ->take(3)
            ->get();

        foreach ($similarForms as $form) {
            $form->badgeClass = $badgeColors[$form->categorie] ?? 'bg-secondary';
        }

        return view('inscription', compact('oneForm', 'similarForms'));
    }

    // public function formInsc(Request $req)
    // {
    //     $req->validate([
    //         'formation_id' => 'required|exists:formations,id',
    //         'message' => 'nullable|string|max:500',
    //         'formation_prix' => 'required|numeric|min:0'
    //     ]);

    //     // Vérification du type d'utilisateur
    //     if (Auth::user()->usertype !== 'user') {
    //         return redirect()->back()->with('error', 
    //             'Seuls les utilisateurs standard peuvent s\'inscrire aux formations. ' .
    //             'Les administrateurs ne peuvent pas s\'inscrire.');
    //     }

    //     $formation = Formation::findOrFail($req->formation_id);
        
    //     // Vérifier la cohérence du prix
    //     if ($formation->prix != $req->formation_prix) {
    //         return redirect()->back()->with('error', 'Le prix de la formation a changé. Veuillez actualiser la page.');
    //     }

    //     if ($formation->status !== 'publiee') {
    //         return redirect()->back()->with('error', 'Cette formation n\'est pas disponible pour l\'inscription.');
    //     }

    //     // Vérifier l'existence d'une inscription
    //     $existing = Inscription::where('user_id', Auth::id())
    //         ->where('formation_id', $formation->id)
    //         ->first();

    //     if ($existing) {
    //         return redirect()->back()->with('warning', 'Vous êtes déjà inscrit à cette formation.');
    //     }

    //     try {
    //         $insc = new Inscription();
    //         $insc->user_id = Auth::id();
    //         $insc->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
    //         $insc->email = Auth::user()->email;
    //         $insc->phone = Auth::user()->phone;
    //         $insc->address = Auth::user()->address;
    //         $insc->message = $req->message;
    //         $insc->formation_id = $formation->id;
    //         $insc->choixForm = $formation->titre;
    //         // $insc->montant = $formation->prix;
    //         // $insc->montant = "15000";
    //         $insc->status = 'Accepté';
    //         $insc->save();

    //         $this->sendInscriptionEmail(Auth::user(), $formation, $insc);

    //         // Mail::to(Auth::user()->email)->send(
    //         //     new infoMail(Auth::user(), $formation, $insc)
    //         // );

    //         return redirect()->back()->with('success', 'Votre demande a été reçue avec succès.');

    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Erreur lors de l\'inscription: ' . $e->getMessage());
    //     }
    // }

    public function formInsc(Request $req)
    {
        $req->validate([
            'formation_id' => 'required|exists:formations,id',
            'message' => 'nullable|string|max:500',
            'formation_prix' => 'required|numeric|min:0'
        ]);

        // Vérification du type d'utilisateur
        if (Auth::user()->usertype !== 'user') {
            return redirect()->back()->with('error', 
                'Seuls les utilisateurs standard peuvent s\'inscrire aux formations. ' .
                'Les administrateurs ne peuvent pas s\'inscrire.');
        }

        $formation = Formation::findOrFail($req->formation_id);
        
        // Vérifier la cohérence du prix
        if ($formation->prix != $req->formation_prix) {
            return redirect()->back()->with('error', 'Le prix de la formation a changé. Veuillez actualiser la page.');
        }

        if ($formation->status !== 'publiee') {
            return redirect()->back()->with('error', 'Cette formation n\'est pas disponible pour l\'inscription.');
        }

        // ⭐⭐ CORRECTION : Vérifier l'existence d'une inscription active (non annulée)
        $existing = Inscription::where('user_id', Auth::id())
            ->where('formation_id', $formation->id)
            ->where('status', '!=', 'Annulé')
            ->first();

        if ($existing) {
            // Vérifier le statut pour donner un message précis
            if ($existing->status === 'En attente') {
                return redirect()->back()->with('warning', 'Vous avez déjà une inscription en attente pour cette formation.');
            } else if ($existing->status === 'Accepté') {
                return redirect()->back()->with('warning', 'Vous êtes déjà inscrit à cette formation.');
            } else if ($existing->status === 'Payé') {
                return redirect()->back()->with('warning', 'Cette formation est déjà payée.');
            }
        }

        try {
            $insc = new Inscription();
            $insc->user_id = Auth::id();
            $insc->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $insc->email = Auth::user()->email;
            $insc->phone = Auth::user()->phone;
            $insc->address = Auth::user()->address;
            $insc->message = $req->message;
            $insc->formation_id = $formation->id;
            $insc->choixForm = $formation->titre;
            
            // ⭐⭐ IMPORTANT : Ne pas assigner de montant ici (colonne inexistante)
            // Si vous avez besoin de stocker le montant dans inscriptions, 
            // vous devez d'abord ajouter la colonne via une migration
            
            // ⭐⭐ CORRECTION : Statut initial doit être "En attente" et non "Accepté"
            // $insc->status = 'En attente';
            $insc->status = 'Accepté';
            $insc->save();

            $this->sendInscriptionEmail(Auth::user(), $formation, $insc);

            return redirect()->back()->with('success', 'Votre demande a été reçue avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'inscription: ' . $e->getMessage());
        }
    }

    public function contactView(){
        return view('contact');
    }

    public function aboutView(){
        return view('about');
    }

    public function storeContact(Request $req){
        // Validation des données de contact
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:500',
        ]);

        // Envoi d'un e-mail de contact (si nécessaire)
        // Mail::to('admin@example.com')->send(new infoMail()); // Remplacez 'admin@example.com' par l'adresse e-mail de destination
        // Enregistrement du message de contact dans la base de données (si nécessaire)
        // Contact::create([
        //     'name' => $req->name,
        //     'email' => $req->email,
        //     'message' => $req->message,
        // ]);
    }

    // User administration

    public function uAdmin(){
            $userName = Auth::user()->name;
            $inscShow = Inscription::where('name', $userName)->get();
            return view('uAdmin.index', compact('inscShow'));
    }

    public function uFormation()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $inscShow = Inscription::where('user_id', $userId)
                ->where('status', '!=', 'Annulé') // ⭐⭐ EXCLURE LES ANNULÉES
                ->get();
            
            return view('uAdmin.forms', compact('inscShow'));
        } else {
            return redirect()->back();
        }
    }

    public function annulerRes($id)
    {
        $inscription = Inscription::findOrFail($id);
        
        // VÉRIFICATION : Ne pas annuler si déjà payé
        if ($inscription->statut_paiement === 'complet') {
            Log::warning('🚨 Tentative d\'annulation d\'une inscription payée', [
                'inscription_id' => $id,
                'statut_paiement' => $inscription->statut_paiement
            ]);
            return redirect()->back()->with('error', 'Cette formation a déjà été payée et ne peut pas être annulée.');
        }

        // VÉRIFICATION : Ne pas annuler si déjà annulée
        if ($inscription->status === 'Annulé') {
            return redirect()->back()->with('info', 'Cette inscription est déjà annulée.');
        }

        // ⭐⭐ CORRECTION : METTRE À JOUR LE STATUT AU LIEU DE SUPPRIMER
        $inscription->update([
            'status' => 'Annulé',
            'date_annulation' => now()
        ]);

        // ⭐⭐ ANNULER LA SESSION STRIPE SI ELLE EXISTE
        if ($inscription->stripe_session_id) {
            try {
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $stripe->checkout->sessions->expire($inscription->stripe_session_id);
                
                Log::info('🔗 SESSION STRIPE EXPIREE AVEC SUCCÈS', [
                    'inscription_id' => $id,
                    'session_id' => $inscription->stripe_session_id
                ]);
            } catch (\Exception $e) {
                Log::warning('⚠️ IMPOSSIBLE D\'EXPIRER LA SESSION STRIPE', [
                    'inscription_id' => $id,
                    'session_id' => $inscription->stripe_session_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('🗑️ INSCRIPTION ANNULÉE (STATUT MIS À JOUR)', [
            'inscription_id' => $id,
            'user_id' => $inscription->user_id,
            'formation' => $inscription->choixForm,
            'nouveau_statut' => 'Annulé'
        ]);

        // Envoyer l'email de confirmation d'annulation
        $this->sendCancellationEmail($inscription);
        // Mail::to($inscription->email)->send(new ReservationAnnulee($inscription));

        return redirect()->back()->with('success', 'Votre réservation a été annulée avec succès.');
    }

    public function afficherConfirmation($id){
        $route = route('annuler_reservation', ['id' => $id]);
        return redirect()->back()->with([
            'message' => 'Souhaitez-vous réellement annuler votre demande ?',
            'type' => 'info',
            'confirm_route' => $route
        ]);
    }

    public function uProfile(){
        if (Auth::id()) {
            $user = Auth::user();
            return view('uAdmin.profile', compact('user'));
        } else {
            return redirect()->back();
        }
    }

    public function uUpdate(Request $req){
        $user = Auth::user();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->phone = $req->phone;
        $user->address = $req->address;
        $user->save();
        return redirect()->back()->with('success', 'Votre profile a été mis à jour avec succès');
    }

    public function uDelete(Request $req){
        $user = Auth::user();
        $user->delete();
        return redirect()->back()->with('success', 'Votre compte a été supprimé avec succès');
    }

    public function uPassword(Request $req){
        $user = Auth::user();
        if (password_verify($req->old_password, $user->password)) {
            if ($req->new_password === $req->confirm_password) {
                $user->password = bcrypt($req->new_password);
                $user->save();
                return redirect()->back()->with('success', 'Votre mot de passe a été mis à jour avec succès');
            } else {
                return redirect()->back()->with('error', 'Les nouveaux mots de passe ne correspondent pas');
            }
        } else {
            return redirect()->back()->with('error', 'L\'ancien mot de passe est incorrect');
        }
    }

    public function uSupport(){
        return view('uAdmin.support');
    }

    // Stripe

    public function showPaymentMethods($inscriptionId){
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $inscription = Inscription::with('formation')
            ->where('id', $inscriptionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Vérifications de sécurité
        if ($inscription->status !== 'Accepté') {
            return redirect()->route('uFormation')->with('error', 'Cette inscription n\'est pas éligible au paiement.');
        }

        if ($inscription->statut_paiement === 'complet') {
            return redirect()->route('uFormation')->with('info', 'Cette formation a déjà été payée.');
        }

        $methodesPaiement = [
            [
                'id' => 'stripe',
                'nom' => 'Carte Bancaire',
                'description' => 'Paiement sécurisé par carte Visa/Mastercard',
                'icone' => 'fa-credit-card',
                'disponible' => true
            ],
            [
                'id' => 'momo', 
                'nom' => 'Mobile Money (Momo)',
                'description' => 'Paiement via votre compte Mobile Money',
                'icone' => 'fa-mobile-alt',
                'disponible' => false,
                'message' => 'Bientôt disponible'
            ],
            [
                'id' => 'airtel_money',
                'nom' => 'Airtel Money',
                'description' => 'Paiement via votre compte Airtel Money',
                'icone' => 'fa-wallet',
                'disponible' => false,
                'message' => 'Bientôt disponible'
            ],
            [
                'id' => 'virement',
                'nom' => 'Virement Bancaire',
                'description' => 'Transfert bancaire traditionnel',
                'icone' => 'fa-university',
                'disponible' => false,
                'message' => 'Bientôt disponible'
            ]
        ];

        return view('uAdmin.choose-method', compact('inscription', 'methodesPaiement'));
    }

    public function processPayment(Request $request, $inscriptionId){
        $request->validate([
            'methode_paiement' => 'required|in:stripe,momo,airtel_money,virement'
        ]);

        $methode = $request->methode_paiement;

        Log::info('🔄 PROCESS PAYMENT DÉMARRÉ', [
            'methode' => $methode,
            'inscription_id' => $inscriptionId,
            'user_id' => Auth::id()
        ]);

        switch ($methode) {
            case 'stripe':
                return $this->checkout($inscriptionId); // → Appel de checkout()
                
            case 'momo':
            case 'airtel_money':
            case 'virement':
                return redirect()->route('uFormation')->with('info', 'Cette méthode de paiement sera bientôt disponible.');
                
            default:
                return redirect()->back()->with('error', 'Méthode de paiement non reconnue.');
        }
    }

    public function checkout($inscriptionId)
    {
        if (!Auth::check()) {
            Log::warning('❌ Utilisateur non authentifié');
            abort(403, 'Vous devez être connecté pour effectuer un paiement.');
        }

        Log::info("🔁 DÉMARRAGE checkout() pour l'inscription ID: $inscriptionId", [
            'user_id' => Auth::id(),
            'ip' => request()->ip()
        ]);

        // Récupération et validation de l'inscription
        $inscription = Inscription::with('formation')->find($inscriptionId);

        // VÉRIFICATION : Inscription existe
        if (!$inscription) {
            Log::error("❌ Inscription introuvable (ID: $inscriptionId)");
            return redirect()->route('uFormation')->with('error', 'Cette inscription n\'existe plus.');
        }

        // VÉRIFICATION : Appartenance
        if ((int)$inscription->user_id !== (int)Auth::id()) {
            Log::warning('❌ Accès interdit à une autre inscription', [
                'connecté' => Auth::id(),
                'propriétaire' => $inscription->user_id,
            ]);
            abort(403, 'Accès interdit.');
        }

        // VÉRIFICATION CRITIQUE : Statut doit être "Accepté"
        if ($inscription->status !== 'Accepté') {
            Log::warning("🚨 Tentative de paiement pour inscription non valide", [
                'inscription_id' => $inscription->id,
                'statut_actuel' => $inscription->status,
                'action_requise' => 'Inscription annulée ou supprimée'
            ]);
            return redirect()->route('uFormation')->with('error', 'Cette inscription n\'est plus valide.');
        }

        // VÉRIFICATION : Ne pas permettre le paiement si déjà payé
        if ($inscription->statut_paiement === 'complet') {
            Log::warning('🚨 Tentative de double paiement', [
                'inscription_id' => $inscriptionId,
                'statut_paiement' => $inscription->statut_paiement
            ]);
            return redirect()->route('uFormation')->with('info', 'Cette formation a déjà été payée.');
        }

        $formation = $inscription->formation;

        if (!$formation || !$formation->stripe_price_id) {
            Log::error('❌ Formation ou prix Stripe manquant', [
                'formation_id' => optional($formation)->id,
                'stripe_price_id' => optional($formation)->stripe_price_id,
            ]);
            return redirect()->back()->with('error', 'Impossible de procéder au paiement : formation non valide.');
        }

        try {
            Log::info('✅ CRÉATION session Stripe...', [
                'formation' => $formation->id,
                'stripe_price_id' => $formation->stripe_price_id,
                'montant' => $inscription->montant
            ]);

            // ⭐⭐ CORRECTION : Utilisation du StripeClient moderne
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            
            $session = $stripe->checkout->sessions->create([
                'line_items' => [[
                    'price' => $formation->stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/user/payment/verify') . '?inscription=' . $inscriptionId . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'inscription_id' => $inscriptionId,
                    'user_id' => Auth::id(),
                    'formation_titre' => $formation->titre
                ],
                'customer_email' => Auth::user()->email,
                'client_reference_id' => 'insc_'.$inscriptionId,
            ]);

            // ⭐⭐ STOCKER LA SESSION_ID POUR POUVOIR L'EXPIRER PLUS TARD
            $inscription->update([
                'stripe_session_id' => $session->id
            ]);

            Log::info('✅ SESSION STRIPE CRÉÉE', [
                'session_id' => $session->id,
                'success_url' => url('/user/payment/verify') . '?inscription=' . $inscriptionId . '&session_id=' . $session->id,
                'checkout_url' => $session->url
            ]);

            // Redirection vers Stripe
            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error('💥 ERREUR checkout(): ' . $e->getMessage(), [
                'inscription_id' => $inscriptionId,
                'error_trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'initialisation du paiement: '.$e->getMessage());
        }
    }

    public function verifyPayment(Request $request)
    {
        $sessionId = $request->get('session_id');
        $inscriptionId = $request->get('inscription');

        Log::info('🎯 VERIFY PAYMENT APPELÉE', [
            'session_id' => $sessionId,
            'inscription_id' => $inscriptionId,
            'user_id' => Auth::id(),
            'ip' => $request->ip()
        ]);

        // Validation des paramètres
        if (!$sessionId || !$inscriptionId) {
            Log::error('❌ PARAMÈTRES MANQUANTS dans verifyPayment', $request->all());
            return redirect()->route('uFormation')->with('error', 'Paramètres de paiement manquants.');
        }

        try {
            // ⭐⭐ CORRECTION : Utilisation du StripeClient moderne
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            
            // Récupération de la session Stripe
            $stripeSession = $stripe->checkout->sessions->retrieve($sessionId);
            
            Log::info('📊 STATUT SESSION STRIPE', [
                'session_id' => $sessionId,
                'payment_status' => $stripeSession->payment_status,
                'payment_intent' => $stripeSession->payment_intent,
                'amount_total' => $stripeSession->amount_total
            ]);

            // ⭐⭐ CORRECTION : Utiliser find() au lieu de findOrFail()
            $inscription = Inscription::with('formation')->find($inscriptionId);

            // Vérifier si l'inscription existe
            if (!$inscription) {
                Log::warning('📭 INSCRIPTION INTROUVABLE', [
                    'inscription_id' => $inscriptionId,
                    'user_id' => Auth::id()
                ]);
                return redirect()->route('payment.expired')->with('error', 'Cette inscription n\'existe plus.');
            }

            // Vérifier si l'inscription est annulée
            if ($inscription->status === 'Annulé') {
                Log::warning('🚫 TENTATIVE DE PAIEMENT SUR INSCRIPTION ANNULÉE', [
                    'inscription_id' => $inscriptionId,
                    'user_id' => Auth::id(),
                    'statut_actuel' => $inscription->status
                ]);
                return redirect()->route('payment.expired')->with('error', 'Cette inscription a été annulée.');
            }

            // VÉRIFICATION DE SÉCURITÉ : Appartenance
            if ((int)$inscription->user_id !== (int)Auth::id()) {
                Log::error('🚨 TENTATIVE ACCÈS FRAUDULEUX', [
                    'user_connecte' => Auth::id(),
                    'proprietaire_inscription' => $inscription->user_id,
                    'session_id' => $sessionId
                ]);
                abort(403, 'Accès non autorisé à cette inscription.');
            }

            // VÉRIFICATION : Statut doit être "Accepté"
            if ($inscription->status !== 'Accepté') {
                Log::warning('⚠️ INSCRIPTION NON ÉLIGIBLE AU PAIEMENT', [
                    'inscription_id' => $inscriptionId,
                    'statut_actuel' => $inscription->status
                ]);
                return redirect()->route('payment.expired')->with('error', 'Cette inscription n\'est pas éligible au paiement.');
            }

            // VÉRIFICATION : Ne pas permettre le paiement si déjà payé
            if ($inscription->statut_paiement === 'complet') {
                Log::warning('💰 TENTATIVE DE DOUBLE PAIEMENT', [
                    'inscription_id' => $inscriptionId,
                    'statut_paiement' => $inscription->statut_paiement
                ]);
                return redirect()->route('uFormation')->with('info', 'Cette formation a déjà été payée.');
            }

            if ($stripeSession->payment_status === 'paid') {
                // Vérifier si déjà traité pour éviter les doublons
                if ($inscription->statut_paiement === 'complet') {
                    Log::info('ℹ️ PAIEMENT DÉJÀ TRAITÉ', [
                        'inscription_id' => $inscriptionId,
                        'statut_actuel' => $inscription->statut_paiement
                    ]);
                    return view('payment.success', compact('inscription'));
                }

                // DÉBUT DE LA TRANSACTION ATOMIQUE
                DB::beginTransaction();

                try {
                    // 1. METTRE À JOUR L'INSCRIPTION
                    $inscription->update([
                        'statut_paiement' => 'complet',
                        'status' => 'Payé',
                        'stripe_session_id' => $sessionId,
                        'payment_date' => now(), 
                    ]);

                    Log::info('✅ INSCRIPTION MIS À JOUR', [
                        'inscription_id' => $inscription->id,
                        'nouveau_statut' => 'Payé'
                    ]);

                    // 2. CRÉER L'ENREGISTREMENT DANS LA TABLE PAIEMENTS
                    $paiement = Paiement::create([
                        'inscription_id' => $inscription->id,
                        'montant' => $inscription->montant,
                        'mode' => 'carte banquaire',
                        'reference' => 'STRIPE_' . substr($sessionId, -20),
                        'statut' => 'complet',
                        'date_paiement' => now(),
                        'stripe_payment_id' => $stripeSession->payment_intent,
                    ]);

                    Log::info('💰 PAIEMENT ENREGISTRÉ', [
                        'paiement_id' => $paiement->id,
                        'inscription_id' => $inscription->id,
                        'montant' => $inscription->montant,
                        'reference' => 'STRIPE_' . substr($sessionId, -20)
                    ]);

                    // 3. ENVOYER L'EMAIL DE CONFIRMATION
                    $this->sendPaymentConfirmationEmail($inscription);
                    // Mail::to($inscription->email)->send(new PaymentConfirmation($inscription));

                    Log::info('📧 EMAIL CONFIRMATION ENVOYÉ', [
                        'email' => $inscription->email,
                        'inscription_id' => $inscription->id
                    ]);

                    // VALIDER LA TRANSACTION
                    DB::commit();

                    Log::info('🎉 PROCESSUS PAIEMENT TERMINÉ AVEC SUCCÈS', [
                        'inscription_id' => $inscription->id,
                        'paiement_id' => $paiement->id,
                        'session_id' => $sessionId
                    ]);

                    // 4. AFFICHER LA PAGE DE SUCCÈS
                    return view('payment.success', compact('inscription'));

                } catch (\Exception $e) {
                    // ANNULER LA TRANSACTION EN CAS D'ERREUR
                    DB::rollBack();
                    
                    Log::error('💥 ERREUR TRANSACTION verifyPayment(): ' . $e->getMessage(), [
                        'inscription_id' => $inscriptionId,
                        'session_id' => $sessionId,
                        'error_trace' => $e->getTraceAsString()
                    ]);
                    
                    return redirect()->route('uFormation')->with('error', 
                        'Erreur lors de l\'enregistrement du paiement. Contactez le support.'
                    );
                }

            } else {
                Log::warning('⚠️ PAIEMENT NON COMPLÉTÉ', [
                    'session_id' => $sessionId,
                    'payment_status' => $stripeSession->payment_status,
                    'inscription_id' => $inscriptionId
                ]);
                
                return redirect()->route('payment.cancel')->with('error', 
                    'Le paiement n\'a pas été effectué. Statut: ' . $stripeSession->payment_status
                );
            }

        } catch (\Exception $e) {
            Log::error('💥 ERREUR GLOBALE verifyPayment(): ' . $e->getMessage(), [
                'session_id' => $sessionId,
                'inscription_id' => $inscriptionId,
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('payment.expired')->with('error', 
                'Erreur de confirmation du paiement. Ce lien a peut-être expiré.'
            );
        }
    }

    public function cancel(){
        Log::info('❌ PAIEMENT ANNULÉ PAR L\'UTILISATEUR', [
            'user_id' => Auth::id(),
            'ip' => request()->ip()
        ]);

        return view('payment.cancel')->with('warning', 'Vous avez annulé le processus de paiement.');
    }

    public function generateStripeLink($inscriptionId){
        $inscription = Inscription::findOrFail($inscriptionId);
        $user = $inscription->user;
        
        if (!$user || !$user->email) {
            Log::warning("❌ Utilisateur ou email manquant", ['inscription_id' => $inscriptionId]);
            return null;
        }

        if ($inscription->status !== 'Accepté') {
            return null;
        }

        $formation = $inscription->formation;

        if (!$formation || !$formation->stripe_price_id) {
            return null;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // ⭐⭐ CORRECTION : Créer d'abord la session
            $session = Session::create([
                'line_items' => [[
                    'price' => $formation->stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // ⭐⭐ CORRECTION : Utiliser '{CHECKOUT_SESSION_ID}'
                'success_url' => url('/user/payment/verify') . '?inscription=' . $inscriptionId . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'inscription_id' => $inscriptionId,
                    'user_id' => $user->id
                ],
                'customer_email' => $user->email,
                'client_reference_id' => 'insc_' . $inscriptionId,
            ]);

            // ⭐⭐ CORRECTION : Maintenant $session est définie
            $lienPaiement = $session->url;

            Log::info('🔗 LIEN STRIPE GÉNÉRÉ', [
                'inscription_id' => $inscriptionId,
                'session_id' => $session->id,
                'lien_paiement' => $lienPaiement
            ]);

            return $lienPaiement;

        } catch (\Exception $e) {
            Log::error('💥 ERREUR generateStripeLink(): ' . $e->getMessage());
            return null;
        }
    }

    public function showLinkExpired(Request $request)
    {
        $inscriptionId = $request->get('inscription');
        $sessionId = $request->get('session_id');
        
        Log::info('🔗 REDIRECTION VERS PAGE EXPIRATION', [
            'inscription_id' => $inscriptionId,
            'session_id' => $sessionId,
            'user_id' => Auth::id(),
            'ip' => $request->ip()
        ]);

        return view('payment.link-expired')->with('error', 'Ce lien de paiement n\'est plus valide.');
    }

    // Envois d'emails centralisés

    private function sendInscriptionEmail($user, $formation, $inscription)
    {
        try {
            Mail::to($user->email)->send(new infoMail($user, $formation, $inscription));
            Log::info('📧 Email d\'inscription envoyé', [
                'user_id' => $user->id,
                'formation' => $formation->titre,
                'email' => $user->email
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi email inscription', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            // Ne pas bloquer le processus si l'email échoue
        }
    }

    private function sendCancellationEmail($inscription)
    {
        try {
            Mail::to($inscription->email)->send(new ReservationAnnulee($inscription));
            Log::info('📧 Email d\'annulation envoyé', [
                'inscription_id' => $inscription->id,
                'email' => $inscription->email
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi email annulation', [
                'error' => $e->getMessage(),
                'inscription_id' => $inscription->id
            ]);
        }
    }

    private function sendPaymentConfirmationEmail($inscription)
    {
        try {
            Mail::to($inscription->email)->send(new PaymentConfirmation($inscription));
            Log::info('📧 Email de confirmation de paiement envoyé', [
                'inscription_id' => $inscription->id,
                'email' => $inscription->email
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi email confirmation paiement', [
                'error' => $e->getMessage(),
                'inscription_id' => $inscription->id
            ]);
        }
    }

}
