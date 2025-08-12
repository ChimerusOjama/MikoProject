<?php

namespace App\Http\Controllers;

use App\Mail\infoMail;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\Request;
use App\Models\User;
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
        // if (Auth::id()) {
        //     return redirect('home');
        // } else {
        //     $forms = Formation::where('status', 'publiee')
        //         ->withCount('inscriptions')
        //         ->orderByDesc('inscriptions_count')
        //         ->take(3)
        //         ->get();

        //     return view('index', compact('forms'));
        // }
    }

    // public function redirect()
    // {
    //     if (!Auth::check()) {
    //         return redirect('/');
    //     }

    //     $usertype = Auth::user()->usertype;

    //     switch ($usertype) {
    //         case 'admin':
    //             return view('admin.index');
    //         case 'user':
    //             $forms = Formation::where('status', 'publiee')
    //                 ->withCount('inscriptions')
    //                 ->orderByDesc('inscriptions_count')
    //                 ->take(3)
    //                 ->get();
    //             return view('index', compact('forms'));
    //         default:
    //             Auth::logout();
    //             return redirect('/')->with('error', 'Type d’utilisateur inconnu. Déconnexion forcée.');
    //     }
    // }

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

    public function formInsc(Request $req)
    {
        if (Auth::id()) {

            $req->validate([
                'formation_id' => 'required|exists:formations,id',
                'message' => 'nullable|string|max:500',
            ]);

            $formation = Formation::findOrFail($req->formation_id);
            if ($formation->status !== 'publiee') {
                return redirect()->back()->with('error', 'Cette formation n\'est pas disponible pour l\'inscription.');
            }

            // Vérifie l'existence d'une inscription
            $existing = Inscription::where('user_id', Auth::id())
                ->where('formation_id', $formation->id)
                ->first();

            if ($existing) {
                return redirect()->back()->with('warning', 'Vous êtes déjà inscrit à cette formation.');
            }

            $insc = new Inscription();
            $insc->user_id = Auth::id();
            $insc->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $insc->email = Auth::user()->email;
            $insc->phone = Auth::user()->phone;
            $insc->address = Auth::user()->address;
            $insc->message = $req->message;
            $insc->formation_id = $formation->id;
            $insc->choixForm = $formation->titre;
            $insc->montant = '14 500 FCFA';
            $insc->status = 'Accepté'; 
            $insc->save();

            if (!$insc) {
                return redirect()->back()->with('error', 'Votre demande a échoué, veuillez réessayer.');
            } else {
                Mail::to(Auth::user()->email)->send(
                    new infoMail(Auth::user(), $formation, $insc)
                );

                return redirect()->back()->with('success', 'Votre demande a été reçue avec succès.');
            }
        } else {
            return redirect()->back()->with('warning', 'Vous devez être connecté pour soumettre une inscription.');
        }
    }



    public function uAdmin(){
        if (Auth::id()) {
            $userName = Auth::user()->name;
            $inscShow = Inscription::where('name', $userName)->get();
            return view('uAdmin.index', compact('inscShow'));

        } else {
            return redirect()->back();
        }
    }

    public function uFormation()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            $inscShow = Inscription::where('user_id', $userId)->get();
            
            return view('uAdmin.forms', compact('inscShow'));
        } else {
            return redirect()->back();
        }
    }

    public function annulerRes($id)
    {
        $delInsc = Inscription::findOrFail($id);
        $userCopy = $delInsc->replicate();
        $delInsc->delete();

        if ($delInsc->status === 'Payé') {
            return redirect()->back()->with('error', 'Cette formation a déjà été payé et ne peut pas être annulée.');
        }

        Mail::to($userCopy->email)->send(new ReservationAnnulee($userCopy));

        return redirect()->back()->with('success', 'Votre réservation a été annulée avec succès.');
    }

    public function afficherConfirmation($id)
    {
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

    //Première version de la fonction checkout
    // public function checkout($inscriptionId)
    // {
    //     if (!Auth::check()) {
    //         Log::warning('Tentative non authentifiée de paiement.');
    //         abort(403, 'Vous devez être connecté pour effectuer un paiement.');
    //     }

    //     $inscription = Inscription::find($inscriptionId);

    //     if (!$inscription) {
    //         Log::error("Inscription introuvable (ID: $inscriptionId)");
    //         abort(404, 'Inscription non trouvée.');
    //     }

    //     if ((int)$inscription->user_id !== (int)Auth::id()) {
    //         Log::warning('Accès interdit à une inscription appartenant à un autre utilisateur.', [
    //             'utilisateur_connecté' => Auth::id(),
    //             'utilisateur_inscription' => $inscription->user_id,
    //         ]);
    //         abort(403, 'Accès interdit.');
    //     }


    //     if ($inscription->status !== 'Accepté') {
    //         Log::info("Tentative de paiement pour une inscription non acceptée (Status: {$inscription->status})", [
    //             'inscription_id' => $inscription->id,
    //         ]);
    //         return redirect()->back()->with('warning', 'Le paiement n’est possible que pour les inscriptions acceptées.');
    //     }

    //     $formation = $inscription->formation;

    //     if (!$formation || !$formation->stripe_price_id) {
    //         Log::error('Formation ou prix Stripe manquant pour l’inscription.', [
    //             'formation_id' => optional($formation)->id,
    //             'stripe_price_id' => optional($formation)->stripe_price_id,
    //         ]);
    //         return redirect()->back()->with('error', 'Impossible de procéder au paiement : formation non valide.');
    //     }

    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     try {
    //         $session = Session::create([
    //             'line_items' => [[
    //                 'price' => $formation->stripe_price_id,
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('payment.success', [
    //                 'inscription' => $inscriptionId,
    //                 'session_id' => '{CHECKOUT_SESSION_ID}'
    //             ]),
    //             'cancel_url' => route('payment.cancel'),
    //             'metadata' => [
    //                 'inscription_id' => $inscriptionId,
    //                 'user_id' => Auth::id()
    //             ]
    //         ]);

    //         Log::info('Stripe session créée avec succès.', [
    //             'session_id' => $session->id,
    //             'session_url' => $session->url
    //         ]);

    //         return redirect($session->url);

    //     } catch (\Exception $e) {
    //         Log::error('Stripe Checkout Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Une erreur est survenue pendant le paiement. Veuillez réessayer.');
    //     }

    // }

    //Force le client Stripe à utiliser le bon fichier de certificat
    // public function checkout($inscriptionId)
    // {
    //     if (!Auth::check()) {
    //         Log::warning('❌ Utilisateur non authentifié');
    //         abort(403, 'Vous devez être connecté pour effectuer un paiement.');
    //     }

    //     Log::info("🔁 Démarrage du paiement pour l'inscription ID: $inscriptionId");

    //     $inscription = Inscription::find($inscriptionId);

    //     if (!$inscription) {
    //         Log::error("❌ Inscription introuvable (ID: $inscriptionId)");
    //         abort(404, 'Inscription non trouvée.');
    //     }

    //     if ((int)$inscription->user_id !== (int)Auth::id()) {
    //         Log::warning('❌ Accès interdit à une autre inscription', [
    //             'connecté' => Auth::id(),
    //             'propriétaire' => $inscription->user_id,
    //         ]);
    //         abort(403, 'Accès interdit.');
    //     }

    //     if ($inscription->status !== 'Accepté') {
    //         Log::info("⛔ Inscription non éligible au paiement (Status: {$inscription->status})", [
    //             'inscription_id' => $inscription->id,
    //         ]);
    //         return redirect()->back()->with('warning', 'Le paiement n’est possible que pour les inscriptions acceptées.');
    //     }

    //     $formation = $inscription->formation;

    //     if (!$formation || !$formation->stripe_price_id) {
    //         Log::error('❌ Formation ou prix Stripe manquant', [
    //             'formation_id' => optional($formation)->id,
    //             'stripe_price_id' => optional($formation)->stripe_price_id,
    //         ]);
    //         return redirect()->back()->with('error', 'Impossible de procéder au paiement : formation non valide.');
    //     }

    //     // Force le client Stripe à utiliser le bon fichier de certificat
    //     putenv('CURL_CA_BUNDLE=' . base_path('storage/app/certs/cacert.pem'));
    //     ini_set('curl.cainfo', base_path('storage/app/certs/cacert.pem'));
    //     ini_set('openssl.cafile', base_path('storage/app/certs/cacert.pem'));


    //     $caBundle = env('CURL_CA_BUNDLE');
    
    //     if (config('app.env') === 'local') {
    //         if ($caBundle && file_exists($caBundle)) {
    //             \Stripe\Stripe::setCABundlePath($caBundle);
    //             Log::info("🔒 Utilisation du bundle de certificats local: $caBundle");
    //         } else {
    //             // Solution de secours pour les environnements sans bundle
    //             Log::warning("⚠️ Bundle de certificats introuvable, tentative de solution alternative");
                
    //             // Tentative de récupération du bundle système
    //             $systemCaBundle = ini_get('curl.cainfo') ?: ini_get('openssl.cafile');
                
    //             if ($systemCaBundle && file_exists($systemCaBundle)) {
    //                 \Stripe\Stripe::setCABundlePath($systemCaBundle);
    //                 Log::info("🔒 Utilisation du bundle système: $systemCaBundle");
    //             } else {
    //                 // Désactivation SSL uniquement en dernier recours
    //                 \Stripe\Stripe::setVerifySslCerts(false);
    //                 Log::warning("⚠️ Vérification SSL désactivée (dernier recours)");
    //             }
    //         }
    //     }

    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     Log::info("🔒 Environnement SSL modifié manuellement : ", [
    //         'curl.cainfo' => ini_get('curl.cainfo'),
    //         'openssl.cafile' => ini_get('openssl.cafile'),
    //         'CURL_CA_BUNDLE' => getenv('CURL_CA_BUNDLE')
    //     ]);


    //     try {
    //         Log::info('✅ Création de session Stripe...', [
    //             'formation' => $formation->id,
    //             'stripe_price_id' => $formation->stripe_price_id,
    //         ]);

    //         $session = Session::create([
    //             'line_items' => [[
    //                 'price' => $formation->stripe_price_id,
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('payment.success', [
    //                 'inscription' => $inscriptionId,
    //                 'session_id' => '{CHECKOUT_SESSION_ID}'
    //             ]),
    //             'cancel_url' => route('payment.cancel'),
    //             'metadata' => [
    //                 'inscription_id' => $inscriptionId,
    //                 'user_id' => Auth::id()
    //             ],
    //             'customer_email' => Auth::user()->email, // Ajout recommandé
    //             'client_reference_id' => 'insc_'.$inscriptionId, // Ajout recommandé
    //         ]);

    //         Log::info('✅ Session Stripe créée avec succès.', [
    //             'session_id' => $session->id,
    //             'checkout_url' => $session->url
    //         ]);

    //         return redirect($session->url);

    //     } catch (\Exception $e) {
    //         Log::error('💥 Stripe Checkout Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Une erreur est survenue pendant le paiement: '.$e->getMessage());
    //     }
    // }

    public function checkout($inscriptionId)
    {
        if (!Auth::check()) {
            Log::warning('❌ Utilisateur non authentifié');
            abort(403, 'Vous devez être connecté pour effectuer un paiement.');
        }

        Log::info("🔁 Démarrage du paiement pour l'inscription ID: $inscriptionId");

        $inscription = Inscription::find($inscriptionId);

        if (!$inscription) {
            Log::error("❌ Inscription introuvable (ID: $inscriptionId)");
            abort(404, 'Inscription non trouvée.');
        }

        if ((int)$inscription->user_id !== (int)Auth::id()) {
            Log::warning('❌ Accès interdit à une autre inscription', [
                'connecté' => Auth::id(),
                'propriétaire' => $inscription->user_id,
            ]);
            abort(403, 'Accès interdit.');
        }

        if ($inscription->status !== 'Accepté') {
            Log::info("⛔ Inscription non éligible au paiement (Status: {$inscription->status})", [
                'inscription_id' => $inscription->id,
            ]);
            return redirect()->back()->with('warning', 'Le paiement n’est possible que pour les inscriptions acceptées.');
        }

        $formation = $inscription->formation;

        if (!$formation || !$formation->stripe_price_id) {
            Log::error('❌ Formation ou prix Stripe manquant', [
                'formation_id' => optional($formation)->id,
                'stripe_price_id' => optional($formation)->stripe_price_id,
            ]);
            return redirect()->back()->with('error', 'Impossible de procéder au paiement : formation non valide.');
        }

        // Force le client Stripe à utiliser le bon fichier de certificat
        putenv('CURL_CA_BUNDLE=' . base_path('storage/app/certs/cacert.pem'));
        ini_set('curl.cainfo', base_path('storage/app/certs/cacert.pem'));
        ini_set('openssl.cafile', base_path('storage/app/certs/cacert.pem'));


        $caBundle = env('CURL_CA_BUNDLE');
    
        if (config('app.env') === 'local') {
            if ($caBundle && file_exists($caBundle)) {
                \Stripe\Stripe::setCABundlePath($caBundle);
                Log::info("🔒 Utilisation du bundle de certificats local: $caBundle");
            } else {
                // Solution de secours pour les environnements sans bundle
                Log::warning("⚠️ Bundle de certificats introuvable, tentative de solution alternative");
                
                // Tentative de récupération du bundle système
                $systemCaBundle = ini_get('curl.cainfo') ?: ini_get('openssl.cafile');
                
                if ($systemCaBundle && file_exists($systemCaBundle)) {
                    \Stripe\Stripe::setCABundlePath($systemCaBundle);
                    Log::info("🔒 Utilisation du bundle système: $systemCaBundle");
                } else {
                    // Désactivation SSL uniquement en dernier recours
                    \Stripe\Stripe::setVerifySslCerts(false);
                    Log::warning("⚠️ Vérification SSL désactivée (dernier recours)");
                }
            }
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        Log::info("🔒 Environnement SSL modifié manuellement : ", [
            'curl.cainfo' => ini_get('curl.cainfo'),
            'openssl.cafile' => ini_get('openssl.cafile'),
            'CURL_CA_BUNDLE' => getenv('CURL_CA_BUNDLE')
        ]);


        try {
            Log::info('✅ Création de session Stripe...', [
                'formation' => $formation->id,
                'stripe_price_id' => $formation->stripe_price_id,
            ]);

            $session = Session::create([
                'line_items' => [[
                    'price' => $formation->stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', [
                    'inscription' => $inscriptionId,
                    'session_id' => '{CHECKOUT_SESSION_ID}'
                ]),
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'inscription_id' => $inscriptionId,
                    'user_id' => Auth::id()
                ],
                'customer_email' => Auth::user()->email, // Ajout recommandé
                'client_reference_id' => 'insc_'.$inscriptionId, // Ajout recommandé
            ]);

            Log::info('✅ Session Stripe créée avec succès.', [
                'session_id' => $session->id,
                'checkout_url' => $session->url
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error('💥 Stripe Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue pendant le paiement: '.$e->getMessage());
        }
    }




    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            if (!$session || $session->payment_status !== 'paid') {
                return redirect()->route('payment.cancel')->with('error', 'Le paiement n\'a pas été effectué.');
            }

            if ($session->payment_status === 'paid') {
                $inscription = Inscription::findOrFail($request->get('inscription'));

                $inscription->update([
                    'status' => 'Payé',
                    'payment_date' => now(),
                    'stripe_session_id' => $sessionId
                ]);

                // ✉️ Envoi du mail ici
                Mail::to($inscription->email)->send(new PaymentConfirmation($inscription));

                return view('payment.success', compact('inscription'));
            }

            return redirect()->route('payment.cancel');

        } catch (\Exception $e) {
            Log::error('Payment verification error: '.$e->getMessage());
            return redirect()->route('uFormation')->with('error', 'Erreur de vérification du paiement');
        }
    }


    public function cancel()
    {
        return view('payment.cancel');
    }

    public function generateStripeLink($inscriptionId)
    {
        $inscription = Inscription::findOrFail($inscriptionId);
        $user = $inscription->user;
        
        if (!$user || !$user->email) {
            Log::warning("Utilisateur introuvable ou email manquant pour l’inscription ID {$inscriptionId}");
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
            $session = Session::create([
                'line_items' => [[
                    'price' => $formation->stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', [
                    'inscription' => $inscriptionId,
                    'session_id' => '{CHECKOUT_SESSION_ID}'
                ]),
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'inscription_id' => $inscriptionId,
                    'user_id' => $user->id
                ],
                'customer_email' => $user->email,
                'client_reference_id' => 'insc_' . $inscriptionId,
            ]);

            if (is_null($lienPaiement)) {
                Log::info("Lien Stripe non généré pour l'inscription #{$inscriptionId}");
            }

            return $session->url;

        } catch (\Exception $e) {
            Log::error('Erreur Stripe (link generation): ' . $e->getMessage());
            return null;
        }
    }


}
