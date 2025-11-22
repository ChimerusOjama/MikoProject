<?php

namespace App\Http\Controllers;

use App\Mail\manualPaymentConfirmation;
use Carbon\Carbon;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use App\Models\Paiement;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{

    public function aIndex()
    {
        // Compteurs basiques
        $totalFormations = Formation::count();
        $totalInscriptions = Inscription::count();
        $totalPaiements = Paiement::whereIn('statut', ['complet', 'partiel'])->sum('montant') ?? 0;

        // Top formations
        $topFormations = Inscription::select('choixForm', DB::raw('COUNT(*) as count'))
            ->groupBy('choixForm')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Inscriptions par mois (compatible multi-DB)
        $inscriptions = Inscription::selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Répartition des statuts
        $statutCounts = Inscription::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Revenus mensuels réels (compatible multi-DB)
        $revenusMensuels = Paiement::whereIn('statut', ['complet', 'partiel'])
            ->selectRaw("TO_CHAR(date_paiement, 'YYYY-MM') as month, SUM(montant) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Modes de paiement réels
        $paiementModes = Paiement::select('mode')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('mode')
            ->get();

        // Dernières inscriptions avec relations
        $dernieresInscriptions = Inscription::with('paiements')->latest()->take(10)->get();

        return view('admin.index', compact(
            'totalFormations',
            'totalInscriptions',
            'totalPaiements',
            'topFormations',
            'inscriptions',
            'statutCounts',
            'revenusMensuels',
            'paiementModes',
            'dernieresInscriptions'
        ));
    }


    public function logout(Request $req){
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

    // Formations
    
    public function allForm(){
        $forms = Formation::all();
        return view('admin.forms.allForm', compact('forms'));
    }

    public function newForm(){
        return view('admin.forms.newForm');
    }

    public function storeForm(Request $req)
    {
        $validated = $req->validate([
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string|max:200',
            'description_longue' => 'nullable|string',
            'categorie' => 'required|string|max:50|in:developpement,bureautique,gestion,langues,marketing,design',
            'niveau' => 'required|string|max:20|in:debutant,intermediaire,avance',
            'prix' => 'required|numeric|min:0|max:1000000',
            'duree_mois' => 'required|integer|min:1|max:36',
            'places_disponibles' => 'nullable|integer|min:0|max:1000',
            'stripe_price_id' => ['required', 'string', 'max:255', 'regex:/^(price|prod)_[a-zA-Z0-9]+$/'],
            'stripe_product_id' => ['nullable', 'string', 'max:255', 'regex:/^(price|prod)_[a-zA-Z0-9]+$/'],
            'status' => 'required|string|in:publiee,brouillon,archivee',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $safeName = Str::slug(pathinfo($req->image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $req->image->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            $path = $req->image->storeAs('formations', $imageName, 'public');

            Formation::create([
                'titre' => $validated['titre'],
                'description_courte' => $validated['description_courte'],
                'description_longue' => $validated['description_longue'] ?? null,
                'categorie' => $validated['categorie'],
                'niveau' => $validated['niveau'],
                'prix' => $validated['prix'],
                'duree_mois' => $validated['duree_mois'],
                'places_disponibles' => $validated['places_disponibles'] ?? null,
                'status' => $validated['status'],
                'stripe_price_id' => $validated['stripe_price_id'],
                'stripe_product_id' => $validated['stripe_product_id'] ?? null,
                'date_debut' => $validated['date_debut'] ?? null,
                'date_fin' => $validated['date_fin'] ?? null,
                'image_url' => Storage::url($path),
            ]);

            return redirect()->back()->with('success', 'Formation créée avec succès');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()]);
        }
    }
    
    public function supForm($id)
    {
        $formation = Formation::find($id);

        if (!$formation) {
            return redirect()->back()->with('error', 'Formation introuvable.');
        }
        $hasInscriptions = Inscription::where('formation_id', $formation->id)->exists();

        if ($hasInscriptions) {
            return redirect()->back()->with('warning', "Impossible de supprimer cette formation car des utilisateurs y sont déjà inscrits.");
        }

        $formation->delete();
        return redirect()->back()->with('success', 'La formation a été supprimée avec succès');
    }


    public function updateView($id){
        $form = Formation::find($id);
        return view('admin.forms.updateForm', compact('form'));
    }
    public function updateForm(Request $req, $id)
    {
        $form = Formation::findOrFail($id);

        if (Inscription::where('formation_id', $form->id)->exists()) {
            return redirect()->back()->withErrors(['error' => "Impossible de modifier cette formation car des utilisateurs y sont déjà inscrits."]);
        }

        $validated = $req->validate([
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string|max:200',
            'description_longue' => 'nullable|string',
            'categorie' => 'required|string|in:developpement,bureautique,gestion,langues,marketing,design',
            'niveau' => 'required|string|in:debutant,intermediaire,avance',
            'prix' => 'required|numeric|min:0|max:1000000',
            'duree_mois' => 'required|integer|min:1|max:36',
            'places_disponibles' => 'nullable|integer|min:1|max:100',
            'stripe_price_id' => ['required', 'string', 'max:255', 'regex:/^(price_|prod_)[a-zA-Z0-9]+$/'],
            'stripe_product_id' => 'nullable|string|max:255',
            'status' => 'required|string|in:publiee,brouillon,archivee',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $form->fill($validated);

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $safeName = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $image->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            if (!file_exists(public_path('formations'))) {
                mkdir(public_path('formations'), 0777, true);
            }

            $image->move(public_path('formations'), $imageName);
            $form->image_url = 'formations/' . $imageName;
        }

        if ($form->save()) {
            return redirect()->route('allForm')->with('success', 'La formation a été mise à jour avec succès.');
        } else {
            return redirect()->back()->withErrors(['error' => "Erreur lors de la mise à jour."]);
        }
    }

    public function archiveForm($id)
    {
        $form = Formation::find($id);
        if (!$form) {
            return redirect()->back()->with('error', 'Formation introuvable.');
        }

        $hasInscriptions = Inscription::where('formation_id', $form->id)->exists();
        if ($hasInscriptions) {
            return redirect()->back()->with('warning', "Impossible d'archiver cette formation car des apprenants y sont déjà inscrits.");
        }

        $form->status = 'archivee';
        $form->save();

        return redirect()->back()->with('success', 'La formation a été archivée avec succès');
    }

    public function archiveView()
    {
        $forms = Formation::where('status', 'archivee')->get();
        return view('admin.archiveForm', compact('forms'));
    }

    // inscriptions

    public function inscriptions(Request $request)
    {
        $query = Inscription::query();

        if ($request->filled('nom')) {
            $query->where('name', 'like', '%' . $request->nom . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('statut')) {
            $query->where('status', $request->statut);
        }

        if ($request->filled('tri')) {
            $query->orderBy('montant', $request->tri);
        }

        // Retourner directement le résultat paginé
        $inscriptions = $query->paginate(10)->withQueryString();

        return view('admin.inscriptions.allInscriptions', [
            'allInsc' => $inscriptions
        ]);
    }

    public function inscView()
    {
        $formations = Formation::all();
        return view('admin.inscriptions.newInscView', compact('formations'));
    }
    public function storeInsc(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'montant' => 'required|numeric|min:0',
            'choixForm' => 'required|string|max:100',
            'message' => 'nullable|string',
        ]);

        try {
            $existingInscription = Inscription::where('choixForm', $validated['choixForm'])
                ->where(function($query) use ($validated) {
                    $query->where('email', $validated['email'])
                        ->orWhere('phone', $validated['phone']);
                })
                ->first();

            if ($existingInscription) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'warning' => 'Cette personne est déjà inscrite à cette formation. Veuillez vérifier.',
                        'existing_id' => $existingInscription->id
                    ]);
            }

            $inscription = new Inscription($validated);
            
            // Ajout des propriétés supplémentaires
            // $inscription->status = 'accepté';
            
            $inscription->save();

            return redirect()->back()
                ->with('success', 'Inscription ajoutée avec succès!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de l\'ajout: ' . $e->getMessage()]);
        }
    }



    public function accepterRes($id)
    {
        $res = Inscription::find($id);
        if ($res->status === 'Accepté') {
            return redirect()->back()->with('warning', 'La demande a déjà été acceptée.');
        }

        $res->status = 'Accepté';
        $res->save();
        return redirect()->back()->with('success', 'Vous avez accepté la demande.');
    }

    public function rejeterRes($id)
    {
        $res = Inscription::find($id);
        if ($res->status === 'Rejeté') {
            return redirect()->back()->with('warning', 'La demande a déjà été rejetée.');
        }

        $res->status = 'Rejeté';
        $res->save();
        return redirect()->back()->with('success', 'Vous avez rejeté la demande.');
    }

    // Utilisateurs

    public function usersView()
    {
        $users = User::where('usertype', 'user')->get();
        return view('admin.allUsers', compact('users'));
    }
    public function newUser()
    {
        return view('admin.newUser');
    }

    public function storeUser(Request $req)
    {
        $validated = $req->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'usertype' => 'required|string|in:admin,formateur,agent,user',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'password' => Hash::make($validated['password']),
                'usertype' => $validated['usertype'],
            ];

            // Gestion de la photo de profil
            if ($req->hasFile('profile_photo')) {
                $file = $req->file('profile_photo');
                $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $extension = $file->getClientOriginalExtension();
                $imageName = $safeName . '_' . time() . '.' . $extension;

                $path = $file->storeAs('profile-photos', $imageName, 'public');
                $userData['profile_photo_path'] = Storage::url($path);
            } else {
                // Photo par défaut si aucune n'est fournie
                $userData['profile_photo_path'] = '/images/default-avatar.png';
            }

            // Création de l'utilisateur
            User::create($userData);

            return redirect()->back()->with('success', 'Utilisateur créé avec succès');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()]);
        }
    }

    public function updateUserView($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }
        return view('admin.updateUser', compact('user'));
    }

    public function updateUser(Request $req, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }

        $validated = $req->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'usertype' => 'required|string|in:admin,formateur,agent,user',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->usertype = $validated['usertype'];

        if ($req->hasFile('profile_photo')) {
            $file = $req->file('profile_photo');
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $file->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            if (!file_exists(public_path('profile-photos'))) {
                mkdir(public_path('profile-photos'), 0777, true);
            }

            $file->move(public_path('profile-photos'), $imageName);
            $user->profile_photo_path = '/profile-photos/' . $imageName;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($user->save()) {
            return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès');
        } else {
            return redirect()->back()->withErrors(['error' => "Erreur lors de la mise à jour."]);
        }
    }

    public function supUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }

        $hasInscriptions = Inscription::where('user_id', $user->id)->exists();
        if ($hasInscriptions) {
            return redirect()->back()->with('warning', "Impossible de supprimer cet utilisateur car il a des inscriptions.");
        }

        $user->delete();
        return redirect()->back()->with('success', 'L\'utilisateur a été supprimé avec succès');
    }

    // Paiements

    // public function allPayments()
    // {
    //     // $payments = Payment::all();
    //     // return view('admin.allPayments', compact('payments'));
    //     return view('admin.allPayments');
    // }

    public function allPayments()
    {

        // Récupération des formations distinctes
        $formations = Inscription::distinct('choixForm')
                                ->pluck('choixForm')
                                ->toArray();

        // Récupération des paiements avec pagination et filtres
        $paiements = Paiement::with('inscription')
            ->when(request('status'), function($query, $status) {
                return $query->where('statut', $status);
            })
            ->when(request('formation'), function($query, $formation) {
                return $query->whereHas('inscription', function($q) use ($formation) {
                    $q->where('choixForm', $formation);
                });
            })
            ->when(request('start_date'), function($query, $startDate) {
                return $query->where('date_paiement', '>=', $startDate);
            })
            ->when(request('end_date'), function($query, $endDate) {
                return $query->where('date_paiement', '<=', $endDate);
            })
            ->when(request('search'), function($query, $search) {
                return $query->whereHas('inscription', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('choixForm', 'like', "%{$search}%");
                });
            })
            ->orderBy('date_paiement', 'desc')
            ->paginate(10);

        // Données pour les cartes
        $totalPaiements = Paiement::sum('montant');
        $paiementEvolution = $this->calculatePaymentEvolution();
        $paiementsEnAttente = Paiement::where('statut', 'En attente')->count();
        $montantEnAttente = Paiement::where('statut', 'En attente')->sum('montant');
        $paiementsReussis = Paiement::where('statut', 'Payé')->count();
        $tauxReussite = $this->calculateSuccessRate();
        $reussiteEvolution = $this->calculateSuccessEvolution();

        // Données pour les graphiques
        $paymentTrends = Paiement::select(
                DB::raw("TO_CHAR(date_paiement, 'YYYY-MM') as month"),
                DB::raw('SUM(montant) as total')
            )
            ->where('statut', 'Payé')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $paymentMethods = Paiement::select('mode', DB::raw('COUNT(*) as count'))
            ->groupBy('mode')
            ->get();

        return view('admin.payments.allPayments', compact(
            'paiements',
            'totalPaiements',
            'paiementEvolution',
            'paiementsEnAttente',
            'montantEnAttente',
            'paiementsReussis',
            'tauxReussite',
            'reussiteEvolution',
            'paymentTrends',
            'paymentMethods',
            'formations'
        ));
    }

    private function calculatePaymentEvolution()
    {
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        $currentMonthTotal = Paiement::where(DB::raw("TO_CHAR(date_paiement, 'YYYY-MM')"), $currentMonth)
            ->where('statut', 'Payé')
            ->sum('montant');
        
        $lastMonthTotal = Paiement::where(DB::raw("TO_CHAR(date_paiement, 'YYYY-MM')"), $lastMonth)
            ->where('statut', 'Payé')
            ->sum('montant');
        
        if ($lastMonthTotal == 0) {
            return $currentMonthTotal > 0 ? 100 : 0;
        }
        
        return round(($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal * 100, 1);
    }

    private function calculateSuccessRate()
    {
        $totalPaiements = Paiement::count();
        $successfulPaiements = Paiement::where('statut', 'Payé')->count();
        
        if ($totalPaiements == 0) return 0;
        
        return round(($successfulPaiements / $totalPaiements) * 100, 1);
    }

    private function calculateSuccessEvolution()
    {
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        // Taux de réussite du mois courant
        $currentMonthTotal = Paiement::where(DB::raw("TO_CHAR(date_paiement, 'YYYY-MM')"), $currentMonth)->count();
        $currentMonthSuccess = Paiement::where(DB::raw("TO_CHAR(date_paiement, 'YYYY-MM')"), $currentMonth)
            ->where('statut', 'Payé')
            ->count();
        
        $currentRate = $currentMonthTotal > 0 ? ($currentMonthSuccess / $currentMonthTotal) * 100 : 0;
        
        // Taux de réussite du mois précédent
        $lastMonthTotal = Paiement::where(DB::raw("TO_CHAR(date_paiement, 'YYYY-MM')"), $lastMonth)->count();
        $lastMonthSuccess = Paiement::where(DB::raw("TO_CHAR(date_paiement, 'YYYY-MM')"), $lastMonth)
            ->where('statut', 'Payé')
            ->count();
        
        $lastRate = $lastMonthTotal > 0 ? ($lastMonthSuccess / $lastMonthTotal) * 100 : 0;
        
        if ($lastRate == 0) {
            return $currentRate > 0 ? 100 : 0;
        }
        
        return round(($currentRate - $lastRate) / $lastRate * 100, 1);
    }

    // public function newPayment()
    // {
    //     return view('admin.payments.newPayment');
    // }

    public function newPayment()
    {
        // Charger quelques inscriptions par défaut
        $defaultInscriptions = Inscription::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.payments.newPayment', [
            'defaultInscriptions' => $defaultInscriptions
        ]);
    }

    public function searchInscriptions(Request $request)
    {
        $searchTerm = $request->input('query');

        $inscriptions = Inscription::with('user')
            ->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%")
                    ->orWhere('choixForm', 'like', "%$searchTerm%")
                    ->orWhere('phone', 'like', "%$searchTerm%");
            })
            ->get()
            ->map(function($inscription) {
                // Calculer le montant total payé pour cette inscription
                $paidAmount = Paiement::where('inscription_id', $inscription->id)->sum('montant');
                $remaining = $inscription->montant - $paidAmount;

                return [
                    'id' => $inscription->id,
                    'name' => $inscription->name,
                    'email' => $inscription->email,
                    'formation' => $inscription->choixForm,
                    'totalAmount' => $inscription->montant,
                    'paidAmount' => $paidAmount,
                    'remaining' => $remaining
                ];
            });

        return response()->json($inscriptions);
    }

    // public function storePayment(Request $request)
    // {
    //     $request->validate([
    //         'inscription_id' => 'required|exists:inscriptions,id',
    //         'amount' => 'required|numeric|min:1',
    //         'payment_date' => 'required|date',
    //         'payment_method' => 'required|string',
    //         'user_email' => 'required|email',
    //         'reference' => 'nullable|string|max:100',
    //         'notes' => 'nullable|string|max:500'
    //     ]);

    //     // Créer le paiement
    //     $paiement = new Paiement();
    //     $paiement->inscription_id = $request->inscription_id;
    //     $paiement->montant = $request->amount;
    //     $paiement->date_paiement = $request->payment_date;
    //     $paiement->mode = 'manuel';
    //     $paiement->reference = $request->reference;
    //     $paiement->notes = $request->notes;
    //     $paiement->save();

    //     // Envoyer l'email de confirmation
    //     try {
    //         Mail::to($request->user_email)->send(new PaymentConfirmation($paiement));
    //     } catch (\Exception $e) {
    //         // Logger l'erreur mais ne pas interrompre le processus
    //         \Log::error('Erreur envoi email confirmation paiement: ' . $e->getMessage());
    //     }

    //     return redirect()->route('allPayments')->with('success', 'Paiement enregistré et notification envoyée !');
    // }


    // public function storePayment(Request $request)
    // {
    //     // Activer le logging détaillé
    //     Log::channel('payments')->info('Tentative d\'enregistrement paiement', $request->all());

    //     try {
    //         // Validation améliorée avec messages personnalisés
    //         $validated = $request->validate([
    //             'inscription_id' => 'required|exists:inscriptions,id',
    //             'amount' => 'required|numeric|min:1',
    //             'statut' => 'required|in:complet,partiel,en_attente,annulé',
    //             'date_paiement' => 'required|date',
    //             'mode' => 'required|in:mobile,carte,virement,espèce,cheque,autre',
    //             'reference' => 'required|unique:paiements,reference|max:100',
    //             'user_email' => 'required|email',
    //             'notes' => 'nullable|string|max:500',
    //             'numeric_remaining' => 'required|numeric|min:0'
    //         ], [
    //             'reference.unique' => 'Cette référence de paiement existe déjà dans le système',
    //             'amount.min' => 'Le montant doit être supérieur à 0',
    //             'statut.in' => 'Statut de paiement invalide'
    //         ]);

    //         // Vérification cohérence montant
    //         $montantSaisi = $request->amount;
    //         $resteAPayer = $request->numeric_remaining;
            
    //         if ($montantSaisi > $resteAPayer) {
    //             Log::warning('Montant incohérent', [
    //                 'saisi' => $montantSaisi,
    //                 'reste' => $resteAPayer
    //             ]);
                
    //             return redirect()->back()
    //                 ->with('error', 'Le montant saisi dépasse le reste à payer!')
    //                 ->withInput();
    //         }

    //         // Création du paiement
    //         $paiement = new Paiement();
    //         $paiement->inscription_id = $validated['inscription_id'];
    //         $paiement->montant = $validated['amount'];
    //         $paiement->mode = $validated['mode'];
    //         $paiement->reference = $validated['reference'];
    //         $paiement->statut = $validated['statut'];
    //         $paiement->date_paiement = $validated['date_paiement'];
    //         $paiement->notes = $validated['notes'];
    //         $paiement->save();

    //         Log::info('Paiement enregistré', ['id' => $paiement->id]);

    //         // Envoi email avec gestion d'erreur
    //         try {
    //             Mail::to($validated['user_email'])->send(new PaymentConfirmation($paiement));
    //             Log::info('Email envoyé', ['email' => $validated['user_email']]);
    //         } catch (\Exception $e) {
    //             Log::error('Erreur envoi email', ['error' => $e->getMessage()]);
    //         }

    //         return redirect()->route('allPayments')->with([
    //             'success' => 'Paiement enregistré avec succès!',
    //             'payment_id' => $paiement->id
    //         ]);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Récupération des erreurs de validation
    //         $errors = $e->validator->errors()->all();
    //         Log::error('Erreur validation', ['errors' => $errors]);
            
    //         return redirect()->back()
    //             ->withErrors($e->validator)
    //             ->with('error', implode('<br>', $errors))
    //             ->withInput();
                
    //     } catch (\Exception $e) {
    //         // Gestion des autres exceptions
    //         Log::critical('Erreur système', [
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine()
    //         ]);
            
    //         return redirect()->back()
    //             ->with('error', 'Erreur système: ' . $e->getMessage())
    //             ->with('error', implode('<br>', $errors))
    //             ->withInput();

    //     } catch (\Exception $e) {
    //         // Gestion des autres exceptions
    //         Log::critical('Erreur système', [
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine()
    //         ]);

    //         return redirect()->back()
    //             ->with('error', 'Erreur système: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    public function storePayment(Request $request)
    {
        Log::channel('payments')->info('Tentative d\'enregistrement paiement', $request->all());

        try {
            // Validation améliorée avec messages personnalisés
            $validated = $request->validate([
                'inscription_id' => 'required|exists:inscriptions,id',
                'amount' => 'required|numeric|min:0',
                'statut' => 'required|in:complet,partiel,en_attente,annulé',
                'date_paiement' => 'required|date',
                'mode' => 'required|in:mobile,carte,virement,espèce,cheque,autre',
                'reference' => 'required|unique:paiements,reference|max:100',
                'user_email' => 'required|email',
                'numeric_remaining' => 'required|numeric|min:0'
            ], [
                'reference.unique' => 'Cette référence de paiement existe déjà dans le système',
                'amount.min' => 'Le montant doit être supérieur ou égal à 0',
                'statut.in' => 'Statut de paiement invalide',
                'inscription_id.exists' => 'L\'inscription sélectionnée n\'existe pas'
            ]);

            // Journalisation des données validées
            Log::channel('payments')->debug('Données validées', [
                'validated' => $validated,
                'resteAPayer' => $validated['numeric_remaining']
            ]);

            // Vérification cohérence montant
            $montantSaisi = $validated['amount'];
            $resteAPayer = $validated['numeric_remaining'];
            $statut = $validated['statut'];
            
            // Contrôle spécifique pour les paiements annulés
            if ($statut === 'annulé' && $montantSaisi != 0) {
                Log::channel('payments')->warning('Montant incohérent pour annulé', [
                    'saisi' => $montantSaisi,
                    'reste' => $resteAPayer
                ]);
                
                return redirect()->back()
                    ->with('error', 'Pour un paiement annulé, le montant doit être 0!')
                    ->withInput();
            }
            
            // Contrôle pour les autres statuts
            if ($statut !== 'annulé' && $montantSaisi > $resteAPayer) {
                Log::channel('payments')->warning('Montant incohérent', [
                    'saisi' => $montantSaisi,
                    'reste' => $resteAPayer,
                    'statut' => $statut
                ]);
                
                return redirect()->back()
                    ->with('error', 'Le montant saisi dépasse le reste à payer!')
                    ->withInput();
            }

            // Création du paiement
            $paiement = new Paiement();
            $paiement->inscription_id = $validated['inscription_id'];
            $paiement->montant = $validated['amount'];
            $paiement->mode = $validated['mode'];
            $paiement->reference = $validated['reference'];
            $paiement->statut = $validated['statut'];
            $paiement->date_paiement = $validated['date_paiement'];
            $paiement->save();

            Log::channel('payments')->info('Paiement enregistré', ['id' => $paiement->id]);

            // Envoi email avec gestion d'erreur
            try {
                if ($statut !== 'annulé') {
                    $paiement->load('inscription');
                    // Mail::to($validated['user_email'])->send(new manualPaymentConfirmation($paiement));
                    $this->sendManualPaymentEmail($paiement, $validated['user_email']);
                    Log::channel('payments')->info('Email envoyé', ['email' => $validated['user_email']]);
                } else {
                    Log::channel('payments')->info('Aucun email envoyé pour paiement annulé');
                }
            } catch (\Exception $e) {
                Log::channel('payments')->error('Erreur envoi email', [
                    'error' => $e->getMessage(),
                    'email' => $validated['user_email']
                ]);
            }

            return redirect()->route('allPayments')->with([
                'success' => 'Paiement enregistré avec succès!',
                'payment_id' => $paiement->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Récupération des erreurs de validation
            $errors = $e->validator->errors()->all();
            Log::channel('payments')->error('Erreur validation', ['errors' => $errors]);
            
            return redirect()->back()
                ->withErrors($e->validator)
                ->with('error', implode('<br>', $errors))
                ->withInput();
                
        } catch (\Exception $e) {
            // Gestion des autres exceptions
            Log::channel('payments')->critical('Erreur système', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur système: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function sendManualPaymentEmail($paiement, $userEmail)
    {
        try {
            if ($paiement->statut !== 'annulé') {
                Mail::to($userEmail)->send(new manualPaymentConfirmation($paiement));
                Log::info('📧 Email de paiement manuel envoyé', [
                    'paiement_id' => $paiement->id,
                    'email' => $userEmail
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi email paiement manuel', [
                'error' => $e->getMessage(),
                'paiement_id' => $paiement->id
            ]);
        }
    }

}