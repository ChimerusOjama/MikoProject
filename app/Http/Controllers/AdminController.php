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
            // Créer le dossier imgsFormation s'il n'existe pas
            $directory = 'imgsFormation';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $safeName = Str::slug(pathinfo($req->image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $req->image->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            // Stocker l'image dans le dossier public
            $path = $req->image->storeAs($directory, $imageName, 'public');

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
                'image_url' => Storage::url($path), // Génère l'URL correcte
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
            // Créer le dossier s'il n'existe pas
            $directory = 'imgsFormation';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Supprimer l'ancienne image si elle existe
            if ($form->image_url) {
                $oldPath = str_replace('/storage/', '', $form->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $safeName = Str::slug(pathinfo($req->image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $req->image->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            $path = $req->image->storeAs($directory, $imageName, 'public');
            $form->image_url = Storage::url($path);
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

    public function uploadSchedule(Request $request)
    {
        $request->validate([
            'schedule_pdf' => 'required|mimes:pdf|max:5120',
        ]);

        // Créer le dossier pdfs s'il n'existe pas
        $directory = 'pdfs';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $fileName = 'emploi_du_temps_' . time() . '.pdf';
        
        // Stocker dans storage/app/public/pdfs/
        $path = $request->file('schedule_pdf')->storeAs($directory, $fileName, 'public');

        // Sauvegarder le chemin dans la base de données ou envoyer une variable de session
        session(['schedule_path' => Storage::url($path)]);

        return redirect()->back()->with('success', 'Emploi du temps mis à jour');
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
            'choixForm' => 'required|string|max:100',
            'message' => 'nullable|string',
        ]);

        try {
            // Récupérer la formation correspondant au choix
            $formation = Formation::where('titre', $validated['choixForm'])->first();
            
            if (!$formation) {
                return back()
                    ->withInput()
                    ->withErrors(['choixForm' => 'La formation sélectionnée n\'existe pas.']);
            }

            // Vérifier l'existence d'une inscription
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
            
            // ⭐⭐ CRITIQUE : Lier l'inscription à la formation pour récupérer le prix
            $inscription->formation_id = $formation->id;
            $inscription->status = 'Accepté';
            
            $inscription->save();

            // ⭐⭐ AJOUT : Créer automatiquement un premier paiement (account) si un montant est fourni
            if ($request->has('montant') && $request->montant > 0) {
                $this->createInitialAccount($inscription, $request->montant, $request);
            }

            return redirect()->back()
                ->with('success', 'Inscription ajoutée avec succès!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de l\'ajout: ' . $e->getMessage()]);
        }
    }

    // ⭐⭐ NOUVELLE MÉTHODE : Créer un account initial
    private function createInitialAccount($inscription, $montant, $request)
    {
        try {
            // Vérifier le montant minimum
            if ($montant < 5000) {
                throw new \Exception('Le montant minimum pour un account est de 5 000 FCFA');
            }

            // Vérifier que le montant ne dépasse pas le prix de la formation
            $formationPrix = $inscription->formation->prix ?? 0;
            if ($montant > $formationPrix) {
                throw new \Exception('Le montant ne peut pas dépasser le prix de la formation (' . number_format($formationPrix, 0, ',', ' ') . ' FCFA)');
            }

            // Créer le paiement
            $paiement = new Paiement();
            $paiement->inscription_id = $inscription->id;
            $paiement->montant = $montant;
            $paiement->mode = $request->account_mode ?? 'especes';
            $paiement->reference = $request->account_reference ?? ('ACC-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3))));
            $paiement->statut = ($montant == $formationPrix) ? 'complet' : 'partiel';
            $paiement->account_type = 'account_1'; // Premier account
            $paiement->type_paiement = 'manuel';
            $paiement->date_paiement = now();
            $paiement->save();

            // Mettre à jour le statut de paiement de l'inscription
            $inscription->statut_paiement = ($montant == $formationPrix) ? 'complet' : 'partiel';
            $inscription->save();

            // Envoyer l'email de confirmation
            if (class_exists(\App\Mail\ManualPaymentConfirmation::class)) {
                Mail::to($inscription->email)->send(new \App\Mail\ManualPaymentConfirmation($paiement));
            }

            Log::info('Account initial créé', [
                'inscription_id' => $inscription->id,
                'paiement_id' => $paiement->id,
                'montant' => $montant
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur création account initial', [
                'error' => $e->getMessage(),
                'inscription_id' => $inscription->id
            ]);
            throw $e;
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

    public function storePayment(Request $request)
{
    // Log de début avec tous les détails
    Log::channel('paiements')->info('🚀 DÉBUT - Enregistrement paiement manuel', [
        'user_id' => Auth::id(),
        'user_email' => Auth::user()->email ?? 'unknown',
        'user_type' => Auth::user()->usertype ?? 'unknown',
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'data' => $request->except(['_token']),
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);

    try {
        // Définir les valeurs autorisées
        $modesAutorises = array_keys(Paiement::MODES);
        $statutsAutorises = array_keys(Paiement::STATUTS);
        
        // ⭐⭐ VÉRIFICATION CRITIQUE : EMPÊCHER LES PAIEMENTS DE 0 FCFA SAUF POUR ANNULÉ
        $montantSaisi = (int)$request->amount;
        $statutDemande = $request->statut;
        
        if ($montantSaisi === 0 && $statutDemande !== 'annulé') {
            Log::channel('paiements')->warning('❌ TENTATIVE PAIEMENT 0 FCFA', [
                'montant' => $montantSaisi,
                'statut' => $statutDemande,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', '❌ Un paiement ne peut pas être de 0 FCFA, sauf pour le statut "annulé".')
                ->withInput();
        }
        
        // ⭐⭐ VÉRIFICATION : MINIMUM 5000 POUR LES PAIEMENTS PARTIELS
        if ($statutDemande === 'partiel' && $montantSaisi > 0 && $montantSaisi < 5000) {
            Log::channel('paiements')->warning('⚠️ MONTANT INFÉRIEUR AU MINIMUM', [
                'montant' => $montantSaisi,
                'minimum' => 5000,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', '⚠️ Le montant minimum pour un paiement partiel (account) est de 5 000 FCFA.')
                ->withInput();
        }

        // Validation
        $validated = $request->validate([
            'inscription_id' => 'required|exists:inscriptions,id',
            'amount' => [
                'required', 
                'integer', 
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    // ⭐⭐ VALIDATION PERSONNALISÉE : 0 SEULEMENT POUR ANNULÉ
                    if ($value == 0 && $request->statut != 'annulé') {
                        $fail('Le montant ne peut pas être 0 FCFA pour ce statut.');
                    }
                    
                    // ⭐⭐ VALIDATION PERSONNALISÉE : MINIMUM 5000 POUR PARTIEL
                    if ($request->statut == 'partiel' && $value > 0 && $value < 5000) {
                        $fail('Le montant minimum pour un paiement partiel est de 5 000 FCFA.');
                    }
                }
            ],
            'statut' => ['required', Rule::in($statutsAutorises)],
            'date_paiement' => 'required|date|before_or_equal:today',
            'mode' => ['required', Rule::in($modesAutorises)],
            'reference' => 'required|unique:paiements,reference|max:100',
            'user_email' => 'required|email',
            'numeric_remaining' => 'required|integer|min:0'
        ], [
            'reference.unique' => 'Cette référence de paiement existe déjà dans le système',
            'amount.min' => 'Le montant doit être supérieur ou égal à 0',
            'amount.integer' => 'Le montant doit être un nombre entier (FCFA)',
            'statut.in' => 'Statut de paiement invalide',
            'mode.in' => 'Mode de paiement invalide',
            'date_paiement.before_or_equal' => 'La date ne peut pas être dans le futur',
            'inscription_id.exists' => 'L\'inscription sélectionnée n\'existe pas'
        ]);

        // Log des données validées
        Log::channel('paiements')->debug('✅ DONNÉES VALIDÉES', array_merge($validated, [
            'reste_a_payer' => $validated['numeric_remaining'],
            'validation_time' => now()->format('H:i:s')
        ]));

        // Vérification cohérence montant
        $montantSaisi = (int)$validated['amount'];
        $resteAPayer = (int)$validated['numeric_remaining'];
        $statut = $validated['statut'];
        $mode = $validated['mode'];
        
        // Vérifier si l'inscription existe
        $inscription = Inscription::with(['formation', 'paiements'])->find($validated['inscription_id']);
        
        if (!$inscription) {
            Log::channel('paiements')->error('❌ INSCRIPTION INTROUVABLE', [
                'inscription_id' => $validated['inscription_id'],
                'user_id' => Auth::id()
            ]);
            return redirect()->back()
                ->with('error', '❌ Inscription introuvable!')
                ->withInput();
        }

        // ⭐⭐ VÉRIFICATION CRITIQUE : L'INSCRIPTION DOIT ÊTRE LIÉE À UNE FORMATION
        if (!$inscription->formation) {
            Log::channel('paiements')->critical('💥 INSCRIPTION SANS FORMATION', [
                'inscription_id' => $inscription->id,
                'choixForm' => $inscription->choixForm,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', '❌ Cette inscription n\'est pas liée à une formation. Veuillez contacter l\'administrateur.')
                ->withInput();
        }

        // Récupérer le montant total de la formation
        $montantTotalFormation = $inscription->formation->prix;
        
        // Récupérer tous les paiements existants (hors annulés)
        $paiementsExistants = $inscription->paiements()
            ->where('statut', '!=', 'annulé')
            ->get();

        // Calculer le total déjà payé
        $totalDejaPaye = $paiementsExistants->sum('montant');
        $nouveauTotalPaye = $totalDejaPaye + $montantSaisi;
        
        // Log des calculs
        Log::channel('paiements')->info('🧮 CALCULS MONTANTS', [
            'inscription_id' => $inscription->id,
            'formation' => $inscription->formation->titre,
            'montant_total_formation' => $montantTotalFormation,
            'total_deja_paye' => $totalDejaPaye,
            'montant_saisi' => $montantSaisi,
            'nouveau_total_paye' => $nouveauTotalPaye,
            'reste_a_payer_avant' => $resteAPayer,
            'reste_calculé' => $montantTotalFormation - $totalDejaPaye
        ]);

        // ⭐⭐ VÉRIFICATION : LE MONTANT SAISI NE DÉPASSE PAS LE RESTE À PAYER
        if ($statut !== 'annulé' && $montantSaisi > $resteAPayer) {
            Log::channel('paiements')->warning('⚠️ MONTANT INCOHÉRENT', [
                'saisi' => $montantSaisi,
                'reste' => $resteAPayer,
                'statut' => $statut,
                'formation_prix' => $montantTotalFormation,
                'deja_paye' => $totalDejaPaye
            ]);
            
            return redirect()->back()
                ->with('error', '❌ Le montant saisi dépasse le reste à payer!')
                ->withInput();
        }

        // ⭐⭐ VÉRIFICATION : POUR LES PAIEMENTS ANNULÉS, MONTANT DOIT ÊTRE 0
        if ($statut === 'annulé' && $montantSaisi != 0) {
            Log::channel('paiements')->warning('⚠️ MONTANT INCOHÉRENT POUR ANNULÉ', [
                'saisi' => $montantSaisi,
                'reste' => $resteAPayer
            ]);
            
            return redirect()->back()
                ->with('error', '❌ Pour un paiement annulé, le montant doit être 0 FCFA!')
                ->withInput();
        }

        // ⭐⭐ DÉTERMINER LE TYPE DE COMPTE (ACCOUNT_TYPE)
        $accountType = 'principal';
        $accountsActifs = $paiementsExistants->where('account_type', '!=', 'principal')->count();
        
        // Log des comptes existants
        Log::channel('paiements')->debug('📊 COMPTES EXISTANTS', [
            'count' => $accountsActifs,
            'details' => $paiementsExistants->map(function($p) {
                return [
                    'id' => $p->id,
                    'montant' => $p->montant,
                    'account_type' => $p->account_type,
                    'statut' => $p->statut
                ];
            })
        ]);

        // Si c'est un paiement partiel et qu'il y a déjà des paiements
        if ($statut === 'partiel' && $montantSaisi > 0) {
            if ($accountsActifs >= 2) {
                Log::channel('paiements')->warning('⚠️ LIMITE D\'ACCOUNTS ATTEINTE', [
                    'accounts_existants' => $accountsActifs,
                    'limite' => 2,
                    'inscription_id' => $inscription->id
                ]);
                return redirect()->back()
                    ->with('error', '❌ Cette inscription a déjà atteint la limite de 2 accounts.')
                    ->withInput();
            }
            
            // Déterminer le numéro d'account
            if ($accountsActifs === 0) {
                $accountType = 'account_1';
            } elseif ($accountsActifs === 1) {
                $accountType = 'account_2';
            }
            
            Log::channel('paiements')->info('🏷️ TYPE D\'ACCOUNT DÉTERMINÉ', [
                'account_type' => $accountType,
                'accounts_actifs' => $accountsActifs
            ]);
            
        } elseif ($statut === 'complet' && $montantSaisi > 0) {
            // Si c'est un paiement complet, vérifier s'il n'y a pas déjà d'autres paiements
            if ($paiementsExistants->count() > 0) {
                Log::channel('paiements')->warning('⚠️ PAIEMENT PRINCIPAL DÉJÀ EXISTANT', [
                    'paiements_existants' => $paiementsExistants->count(),
                    'details' => $paiementsExistants->pluck('account_type')
                ]);
                return redirect()->back()
                    ->with('error', '❌ Un paiement principal existe déjà pour cette inscription. Utilisez le statut "partiel" pour ajouter un account.')
                    ->withInput();
            }
            $accountType = 'principal';
        } elseif ($statut === 'annulé') {
            $accountType = 'principal'; // Les annulations sont toujours de type principal
        }

        // ⭐⭐ CRÉATION DU PAIEMENT
        DB::beginTransaction();
        
        try {
            $paiement = new Paiement();
            $paiement->inscription_id = $validated['inscription_id'];
            $paiement->montant = $montantSaisi;
            $paiement->mode = $validated['mode'];
            $paiement->reference = $validated['reference'];
            $paiement->statut = $validated['statut'];
            $paiement->account_type = $accountType;
            $paiement->type_paiement = 'manuel';
            $paiement->date_paiement = $validated['date_paiement'];
            
            Log::channel('paiements')->info('💾 SAUVEGARDE PAIEMENT EN COURS', [
                'paiement_data' => [
                    'inscription_id' => $paiement->inscription_id,
                    'montant' => $paiement->montant,
                    'mode' => $paiement->mode,
                    'reference' => $paiement->reference,
                    'statut' => $paiement->statut,
                    'account_type' => $paiement->account_type
                ]
            ]);

            $paiement->save();

            Log::channel('paiements')->info('✅ PAIEMENT ENREGISTRÉ', [
                'paiement_id' => $paiement->id,
                'inscription_id' => $paiement->inscription_id,
                'montant' => $paiement->montant,
                'mode' => $paiement->mode_label,
                'statut' => $paiement->statut_label,
                'account_type' => $paiement->account_type_label,
                'reference' => $paiement->reference,
                'date_paiement' => $paiement->date_paiement->format('d/m/Y')
            ]);

            // ⭐⭐ METTRE À JOUR LE STATUT DE PAIEMENT DE L'INSCRIPTION
            if ($nouveauTotalPaye >= $montantTotalFormation) {
                $inscription->statut_paiement = 'complet';
                Log::channel('paiements')->info('💰 INSCRIPTION PAYÉE COMPLÈTEMENT', [
                    'inscription_id' => $inscription->id,
                    'total_paye' => $nouveauTotalPaye,
                    'montant_total' => $montantTotalFormation
                ]);
            } elseif ($nouveauTotalPaye > 0) {
                $inscription->statut_paiement = 'partiel';
                Log::channel('paiements')->info('💳 INSCRIPTION PAYÉE PARTIELLEMENT', [
                    'inscription_id' => $inscription->id,
                    'total_paye' => $nouveauTotalPaye,
                    'montant_total' => $montantTotalFormation,
                    'reste' => $montantTotalFormation - $nouveauTotalPaye
                ]);
            } else {
                $inscription->statut_paiement = 'non_payé';
                Log::channel('paiements')->info('❌ INSCRIPTION NON PAYÉE', [
                    'inscription_id' => $inscription->id,
                    'total_paye' => $nouveauTotalPaye
                ]);
            }
            
            $inscription->save();

            // ⭐⭐ ENVOI EMAIL DE CONFIRMATION
            if ($statut !== 'annulé' && $montantSaisi > 0) {
                try {
                    // Charger les données nécessaires pour l'email
                    $paiement->load('inscription');
                    
                    // Vérifier si la classe Mail existe
                    if (class_exists(\App\Mail\ManualPaymentConfirmation::class)) {
                        Mail::to($validated['user_email'])->send(new \App\Mail\ManualPaymentConfirmation($paiement));
                        Log::channel('paiements')->info('📧 EMAIL DE CONFIRMATION ENVOYÉ', [
                            'email' => $validated['user_email'],
                            'paiement_id' => $paiement->id,
                            'client' => $inscription->name
                        ]);
                    } else {
                        Log::channel('paiements')->warning('⚠️ CLASSE ManualPaymentConfirmation NON TROUVÉE');
                    }
                } catch (\Exception $e) {
                    Log::channel('paiements')->error('❌ ERREUR ENVOI EMAIL', [
                        'error' => $e->getMessage(),
                        'email' => $validated['user_email'],
                        'paiement_id' => $paiement->id
                    ]);
                    // Ne pas retourner d'erreur à l'utilisateur si l'email échoue
                }
            } else {
                Log::channel('paiements')->info('📭 AUCUN EMAIL ENVOYÉ POUR PAIEMENT ANNULÉ OU MONTANT NUL');
            }

            DB::commit();

            // Log de succès
            Log::channel('paiements')->info('🎉 FIN - PAIEMENT ENREGISTRÉ AVEC SUCCÈS', [
                'paiement_id' => $paiement->id,
                'inscription_id' => $inscription->id,
                'formation' => $inscription->formation->titre,
                'montant_total_formation' => $montantTotalFormation,
                'total_paye' => $nouveauTotalPaye,
                'reste_a_payer' => max(0, $montantTotalFormation - $nouveauTotalPaye),
                'user_id' => Auth::id(),
                'timestamp' => now()->format('H:i:s')
            ]);

            return redirect()->route('allPayments')->with([
                'success' => '✅ Paiement enregistré avec succès!',
                'payment_id' => $paiement->id,
                'account_type' => $paiement->account_type_label
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::channel('paiements')->critical('💥 ERREUR TRANSACTION', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            throw $e;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Récupération des erreurs de validation
        $errors = $e->validator->errors()->all();
        Log::channel('paiements')->error('❌ ERREUR VALIDATION', [
            'errors' => $errors,
            'user_id' => Auth::id(),
            'data' => $request->except(['_token'])
        ]);
        
        return redirect()->back()
            ->withErrors($e->validator)
            ->with('error', '❌ ' . implode('<br>❌ ', $errors))
            ->withInput();
            
    } catch (\Exception $e) {
        // Gestion des autres exceptions
        Log::channel('paiements')->critical('💥 ERREUR SYSTÈME', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'ip' => $request->ip()
        ]);
        
        return redirect()->back()
            ->with('error', '💥 Erreur système: ' . $e->getMessage())
            ->withInput();
    }
}

    // public function storePayment(Request $request)
    // {
    //     // Log de début
    //     Log::channel('paiements')->info('🚀 DÉBUT - Enregistrement paiement manuel', [
    //         'user_id' => Auth::id(),
    //         'user_email' => Auth::user()->email ?? 'unknown',
    //         'ip' => $request->ip(),
    //         'data' => $request->except(['_token']),
    //         'timestamp' => now()
    //     ]);

    //     try {
    //         // Définir les valeurs autorisées
    //         $modesAutorises = ['mobile money', 'carte banquaire', 'airtel money', 'especes'];
    //         $statutsAutorises = ['complet', 'partiel', 'annulé'];
            
    //         // Validation
    //         $validated = $request->validate([
    //             'inscription_id' => 'required|exists:inscriptions,id',
    //             'amount' => 'required|integer|min:0',
    //             'statut' => ['required', Rule::in($statutsAutorises)],
    //             'date_paiement' => 'required|date',
    //             'mode' => ['required', Rule::in($modesAutorises)],
    //             'reference' => 'required|unique:paiements,reference|max:100',
    //             'user_email' => 'required|email',
    //             'numeric_remaining' => 'required|integer|min:0'
    //         ], [
    //             'reference.unique' => 'Cette référence de paiement existe déjà dans le système',
    //             'amount.min' => 'Le montant doit être supérieur ou égal à 0',
    //             'amount.integer' => 'Le montant doit être un nombre entier (FCFA)',
    //             'statut.in' => 'Statut de paiement invalide',
    //             'mode.in' => 'Mode de paiement invalide',
    //             'inscription_id.exists' => 'L\'inscription sélectionnée n\'existe pas'
    //         ]);

    //         // Log des données validées
    //         Log::channel('paiements')->debug('✅ Données validées', $validated);

    //         // Vérification cohérence montant
    //         $montantSaisi = (int)$validated['amount'];
    //         $resteAPayer = (int)$validated['numeric_remaining'];
    //         $statut = $validated['statut'];
    //         $mode = $validated['mode'];
            
    //         // Vérifier si l'inscription existe
    //         $inscription = Inscription::with('formation')->find($validated['inscription_id']);
    //         if (!$inscription) {
    //             Log::channel('paiements')->error('❌ Inscription introuvable', [
    //                 'inscription_id' => $validated['inscription_id']
    //             ]);
    //             return redirect()->back()
    //                 ->with('error', 'Inscription introuvable!')
    //                 ->withInput();
    //         }

    //         // Log de l'inscription trouvée
    //         Log::channel('paiements')->info('📄 Inscription trouvée', [
    //             'inscription_id' => $inscription->id,
    //             'formation' => $inscription->choixForm,
    //             'user_name' => $inscription->name
    //         ]);

    //         // Récupérer le montant total de la formation
    //         $montantTotalFormation = $inscription->formation ? $inscription->formation->prix : 0;
            
    //         // Récupérer tous les paiements existants (hors annulés)
    //         $paiementsExistants = Paiement::where('inscription_id', $inscription->id)
    //             ->where('statut', '!=', 'annulé')
    //             ->get();

    //         // Calculer le total déjà payé
    //         $totalDejaPaye = $paiementsExistants->sum('montant');
    //         $nouveauTotalPaye = $totalDejaPaye + $montantSaisi;
            
    //         // Log des calculs
    //         Log::channel('paiements')->debug('🧮 Calculs montants', [
    //             'montant_total_formation' => $montantTotalFormation,
    //             'total_deja_paye' => $totalDejaPaye,
    //             'montant_saisi' => $montantSaisi,
    //             'nouveau_total_paye' => $nouveauTotalPaye,
    //             'reste_a_payer_avant' => $resteAPayer
    //         ]);

    //         // Vérification cohérence montant
    //         if ($statut !== 'annulé' && $montantSaisi > $resteAPayer) {
    //             Log::channel('paiements')->warning('⚠️ Montant incohérent', [
    //                 'saisi' => $montantSaisi,
    //                 'reste' => $resteAPayer,
    //                 'statut' => $statut
    //             ]);
                
    //             return redirect()->back()
    //                 ->with('error', 'Le montant saisi dépasse le reste à payer!')
    //                 ->withInput();
    //         }

    //         // Contrôle spécifique pour les paiements annulés
    //         if ($statut === 'annulé' && $montantSaisi != 0) {
    //             Log::channel('paiements')->warning('⚠️ Montant incohérent pour annulé', [
    //                 'saisi' => $montantSaisi,
    //                 'reste' => $resteAPayer
    //             ]);
                
    //             return redirect()->back()
    //                 ->with('error', 'Pour un paiement annulé, le montant doit être 0!')
    //                 ->withInput();
    //         }

    //         // Vérifier si le montant est inférieur au minimum pour un account (5000 FCFA)
    //         if ($statut === 'partiel' && $montantSaisi > 0 && $montantSaisi < 5000) {
    //             Log::channel('paiements')->warning('⚠️ Montant inférieur au minimum', [
    //                 'saisi' => $montantSaisi,
    //                 'minimum' => 5000
    //             ]);
    //             return redirect()->back()
    //                 ->with('error', 'Le montant minimum pour un paiement partiel (account) est de 5 000 FCFA.')
    //                 ->withInput();
    //         }

    //         // Déterminer le type de compte (account_type)
    //         $accountType = 'principal';
    //         $accountsActifs = $paiementsExistants->where('account_type', '!=', 'principal')->count();
            
    //         // Log des comptes existants
    //         Log::channel('paiements')->debug('📊 Comptes existants', [
    //             'count' => $accountsActifs,
    //             'paiements' => $paiementsExistants->pluck('account_type')
    //         ]);

    //         // Si c'est un paiement partiel et qu'il y a déjà des paiements
    //         if ($statut === 'partiel' && $montantSaisi > 0) {
    //             if ($accountsActifs >= 2) {
    //                 Log::channel('paiements')->warning('⚠️ Limite d\'accounts atteinte', [
    //                     'accounts_existants' => $accountsActifs,
    //                     'limite' => 2
    //                 ]);
    //                 return redirect()->back()
    //                     ->with('error', 'Cette inscription a déjà atteint la limite de 2 accounts.')
    //                     ->withInput();
    //             }
                
    //             // Déterminer le numéro d'account
    //             if ($accountsActifs === 0) {
    //                 $accountType = 'account_1';
    //             } elseif ($accountsActifs === 1) {
    //                 $accountType = 'account_2';
    //             }
    //         } elseif ($statut === 'complet' && $montantSaisi > 0) {
    //             // Si c'est un paiement complet, vérifier s'il n'y a pas déjà d'autres paiements
    //             if ($paiementsExistants->count() > 0) {
    //                 Log::channel('paiements')->warning('⚠️ Paiement principal déjà existant', [
    //                     'paiements_existants' => $paiementsExistants->count()
    //                 ]);
    //                 return redirect()->back()
    //                     ->with('error', 'Un paiement principal existe déjà pour cette inscription. Utilisez le statut "partiel" pour ajouter un account.')
    //                     ->withInput();
    //             }
    //             $accountType = 'principal';
    //         }

    //         // Création du paiement
    //         $paiement = new Paiement();
    //         $paiement->inscription_id = $validated['inscription_id'];
    //         $paiement->montant = $montantSaisi;
    //         $paiement->mode = $validated['mode'];
    //         $paiement->reference = $validated['reference'];
    //         $paiement->statut = $validated['statut'];
    //         $paiement->account_type = $accountType;
    //         $paiement->type_paiement = 'manuel';
    //         $paiement->date_paiement = $validated['date_paiement'];
            
    //         // Log avant sauvegarde
    //         Log::channel('paiements')->info('💾 Sauvegarde paiement en cours', [
    //             'paiement_data' => $paiement->toArray()
    //         ]);

    //         $paiement->save();

    //         // Log après sauvegarde
    //         Log::channel('paiements')->info('✅ Paiement enregistré', [
    //             'paiement_id' => $paiement->id,
    //             'inscription_id' => $paiement->inscription_id,
    //             'montant' => $paiement->montant,
    //             'mode' => $paiement->mode,
    //             'statut' => $paiement->statut,
    //             'account_type' => $paiement->account_type,
    //             'reference' => $paiement->reference
    //         ]);

    //         // Mettre à jour le statut de paiement de l'inscription
    //         if ($nouveauTotalPaye >= $montantTotalFormation) {
    //             $inscription->statut_paiement = 'complet';
    //             Log::channel('paiements')->info('💰 Inscription payée complètement', [
    //                 'inscription_id' => $inscription->id,
    //                 'total_paye' => $nouveauTotalPaye,
    //                 'montant_total' => $montantTotalFormation
    //             ]);
    //         } elseif ($nouveauTotalPaye > 0) {
    //             $inscription->statut_paiement = 'partiel';
    //             Log::channel('paiements')->info('💳 Inscription payée partiellement', [
    //                 'inscription_id' => $inscription->id,
    //                 'total_paye' => $nouveauTotalPaye,
    //                 'montant_total' => $montantTotalFormation,
    //                 'reste' => $montantTotalFormation - $nouveauTotalPaye
    //             ]);
    //         } else {
    //             $inscription->statut_paiement = 'non_payé';
    //             Log::channel('paiements')->info('❌ Inscription non payée', [
    //                 'inscription_id' => $inscription->id,
    //                 'total_paye' => $nouveauTotalPaye
    //             ]);
    //         }
            
    //         $inscription->save();

    //         // Envoi email avec gestion d'erreur
    //         try {
    //             if ($statut !== 'annulé' && $montantSaisi > 0) {
    //                 // Charger les données nécessaires pour l'email
    //                 $paiement->load('inscription');
                    
    //                 // Vérifier si la classe Mail existe
    //                 if (class_exists(\App\Mail\ManualPaymentConfirmation::class)) {
    //                     Mail::to($validated['user_email'])->send(new \App\Mail\ManualPaymentConfirmation($paiement));
    //                     Log::channel('paiements')->info('📧 Email de confirmation envoyé', [
    //                         'email' => $validated['user_email'],
    //                         'paiement_id' => $paiement->id
    //                     ]);
    //                 } else {
    //                     Log::channel('paiements')->warning('⚠️ Classe ManualPaymentConfirmation non trouvée');
    //                 }
    //             } else {
    //                 Log::channel('paiements')->info('📭 Aucun email envoyé pour paiement annulé ou montant nul');
    //             }
    //         } catch (\Exception $e) {
    //             Log::channel('paiements')->error('❌ Erreur envoi email', [
    //                 'error' => $e->getMessage(),
    //                 'email' => $validated['user_email'],
    //                 'paiement_id' => $paiement->id
    //             ]);
    //             // Ne pas retourner d'erreur à l'utilisateur si l'email échoue
    //         }

    //         // Log de fin
    //         Log::channel('paiements')->info('🎉 FIN - Paiement enregistré avec succès', [
    //             'paiement_id' => $paiement->id,
    //             'inscription_id' => $inscription->id,
    //             'user_id' => Auth::id(),
    //             'timestamp' => now()
    //         ]);

    //         return redirect()->route('allPayments')->with([
    //             'success' => 'Paiement enregistré avec succès!',
    //             'payment_id' => $paiement->id
    //         ]);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Récupération des erreurs de validation
    //         $errors = $e->validator->errors()->all();
    //         Log::channel('paiements')->error('❌ Erreur validation', [
    //             'errors' => $errors,
    //             'user_id' => Auth::id()
    //         ]);
            
    //         return redirect()->back()
    //             ->withErrors($e->validator)
    //             ->with('error', implode('<br>', $errors))
    //             ->withInput();
                
    //     } catch (\Exception $e) {
    //         // Gestion des autres exceptions
    //         Log::channel('paiements')->critical('💥 ERREUR SYSTÈME', [
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //             'trace' => $e->getTraceAsString(),
    //             'user_id' => Auth::id()
    //         ]);
            
    //         return redirect()->back()
    //             ->with('error', 'Erreur système: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    public function searchInscriptions(Request $request)
{
    Log::info('Recherche d\'inscriptions', ['query' => $request->input('query')]);
    
    $searchTerm = $request->input('query');

    $inscriptions = Inscription::with(['formation', 'paiements'])
        ->where(function($query) use ($searchTerm) {
            $query->where('name', 'like', "%$searchTerm%")
                ->orWhere('email', 'like', "%$searchTerm%")
                ->orWhere('choixForm', 'like', "%$searchTerm%")
                ->orWhere('phone', 'like', "%$searchTerm%");
        })
        ->get()
        ->map(function($inscription) {
            // ⭐⭐ CORRECTION : Vérifier si la formation existe
            $montantTotal = $inscription->formation ? $inscription->formation->prix : 0;
            
            // Calculer le montant total payé (hors annulés)
            $paidAmount = $inscription->paiements
                ->where('statut', '!=', 'annulé')
                ->sum('montant');
            
            $remaining = max(0, $montantTotal - $paidAmount);

            return [
                'id' => $inscription->id,
                'name' => $inscription->name,
                'email' => $inscription->email,
                'formation' => $inscription->choixForm,
                'totalAmount' => $montantTotal,
                'paidAmount' => $paidAmount,
                'remaining' => $remaining,
                'has_formation' => !is_null($inscription->formation) // ⭐⭐ Ajout pour debug
            ];
        });

    Log::info('Résultats de recherche', [
        'count' => $inscriptions->count(),
        'debug' => $inscriptions->map(function($insc) {
            return [
                'nom' => $insc['name'],
                'montant_total' => $insc['totalAmount'],
                'has_formation' => $insc['has_formation']
            ];
        })
    ]);
    
    return response()->json($inscriptions);
}

    // public function searchInscriptions(Request $request)
    // {
    //     Log::info('🔍 Recherche d\'inscriptions', [
    //         'query' => $request->input('query'),
    //         'user_id' => Auth::id(),
    //         'ip' => $request->ip()
    //     ]);
        
    //     $searchTerm = $request->input('query');

    //     $inscriptions = Inscription::with(['formation', 'paiements'])
    //         ->where(function($query) use ($searchTerm) {
    //             $query->where('name', 'like', "%$searchTerm%")
    //                 ->orWhere('email', 'like', "%$searchTerm%")
    //                 ->orWhere('choixForm', 'like', "%$searchTerm%")
    //                 ->orWhere('phone', 'like', "%$searchTerm%");
    //         })
    //         ->get()
    //         ->map(function($inscription) {
    //             // Calculer les montants à partir de la formation et des paiements
    //             $montantTotal = $inscription->formation ? $inscription->formation->prix : 0;
    //             $montantPaye = $inscription->paiements
    //                 ->where('statut', '!=', 'annulé')
    //                 ->sum('montant');
    //             $montantRestant = max(0, $montantTotal - $montantPaye);

    //             return [
    //                 'id' => $inscription->id,
    //                 'name' => $inscription->name,
    //                 'email' => $inscription->email,
    //                 'formation' => $inscription->choixForm,
    //                 'montant_total' => $montantTotal,
    //                 'totalAmount' => $montantTotal,
    //                 'paidAmount' => $montantPaye,
    //                 'remaining' => $montantRestant
    //             ];
    //         });

    //     Log::info('📊 Résultats de recherche', [
    //         'count' => $inscriptions->count(),
    //         'user_id' => Auth::id()
    //     ]);
        
    //     return response()->json($inscriptions);
    // }

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

    public function fixInscriptionWithoutFormation($inscriptionId)
{
    $inscription = Inscription::find($inscriptionId);
    
    if (!$inscription) {
        return redirect()->back()->with('error', 'Inscription introuvable');
    }
    
    if (!$inscription->formation_id) {
        // Trouver la formation par le titre
        $formation = Formation::where('titre', $inscription->choixForm)->first();
        
        if ($formation) {
            $inscription->formation_id = $formation->id;
            $inscription->save();
            
            Log::info('Inscription réparée', [
                'inscription_id' => $inscriptionId,
                'formation_id' => $formation->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('success', 'Inscription liée à la formation avec succès');
        } else {
            return redirect()->back()->with('error', 'Formation non trouvée pour ' . $inscription->choixForm);
        }
    }
    
    return redirect()->back()->with('info', 'L\'inscription est déjà liée à une formation');
}

public function updatePaymentView($id)
{
    $paiement = Paiement::with('inscription')->find($id);
    
    if (!$paiement) {
        return redirect()->route('allPayments')
            ->with('error', 'Paiement introuvable');
    }
    
    return view('admin.payments.editPayment', compact('paiement'));
}

}