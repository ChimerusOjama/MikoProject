<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    //
    public function index()
    {
        return view('admin.index');
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
    
    public function allForm(){
        $forms = Formation::all();
        return view('admin.allForm', compact('forms'));
    }

    public function newForm(){
        return view('admin.newForm');
    }
    public function storeForm(Request $req)
    {
        $validated = $req->validate([
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string',
            'categorie' => 'required|string|max:50|in:informatique,gestion,langues',
            'niveau' => 'required|string|max:20|in:debutant,intermediaire,avance',
            'prix' => 'required|numeric|min:0|max:100000',
            'duree_mois' => 'required|integer|min:1|max:24',
            // 'stripe_price_id' => ['required', 'string', 'max:255', 'regex:/^price_[a-zA-Z0-9]+$/'],
            'stripe_price_id' => ['required', 'string', 'max:255', 'regex:/^prod_[a-zA-Z0-9]+$/'],
            'status' => 'required|string|in:publiee,brouillon,archivee',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $safeName = Str::slug(pathinfo($req->image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $req->image->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            $path = $req->image->storeAs('formations', $imageName, 'public');

            Formation::create([
                'titre' => $validated['titre'],
                'description_courte' => $validated['description_courte'],
                'categorie' => $validated['categorie'],
                'niveau' => $validated['niveau'],
                'prix' => $validated['prix'],
                'duree_mois' => $validated['duree_mois'],
                'status' => $validated['status'],
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
        return view('admin.updateForm', compact('form'));
    }
    public function updateForm(Request $req, $id)
    {
        $form = Formation::find($id);

        $hasInscriptions = Inscription::where('formation_id', $form->id)->exists();
        if ($hasInscriptions) {
            return redirect()->back()->withErrors(['error' => "Impossible de modifier cette formation car des utilisateurs y sont déjà inscrits."]);
        }

        $validated = $req->validate([
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string',
            'categorie' => 'required|string|max:50|in:informatique,gestion,langues',
            'niveau' => 'required|string|max:20|in:debutant,intermediaire,avance',
            'prix' => 'required|numeric|min:0|max:100000',
            'duree_mois' => 'required|integer|min:1|max:24',
            // 'stripe_price_id' => ['required', 'string', 'max:255', 'regex:/^price_[a-zA-Z0-9]+$/'],
            'stripe_price_id' => ['required', 'string', 'max:255', 'regex:/^prod_[a-zA-Z0-9]+$/'],
            'status' => 'required|string|in:publiee,brouillon,archivee',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $form->titre = $validated['titre'];
        $form->description_courte = $validated['description_courte'];
        $form->categorie = $validated['categorie'];
        $form->niveau = $validated['niveau'];
        $form->prix = $validated['prix'];
        $form->duree_mois = $validated['duree_mois'];
        $form->status = $validated['status'];

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $safeName = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $image->getClientOriginalExtension();
            $imageName = $safeName . '_' . time() . '.' . $extension;

            if (!file_exists(public_path('formations'))) {
                mkdir(public_path('formations'), 0777, true);
            }

            $image->move(public_path('formations'), $imageName);
            $imagePath = 'formations/' . $imageName;

            $form->image_url = $imagePath;
        }

        $result = $form->save();

        if ($result) {
            return redirect()->back()->with('success', 'La formation a été mise à jour avec succès');
        } else {
            return redirect()->back()->withErrors(['error' => "Erreur lors de la mise à jour."]);
        }
    }

    public function reserveView(){
        $allInsc = Inscription::orderBy('created_at', 'desc')->get();
        return view('admin.fromUserReserve', compact('allInsc'));
    }
    public function aInscUser(Request $request)
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
            // Création d'une nouvelle inscription
            $inscription = new Inscription();
            $inscription->name = $validated['name'];
            $inscription->email = $validated['email'];
            $inscription->phone = $validated['phone'];
            $inscription->address = $validated['address'] ?? null;
            $inscription->montant = $validated['montant'];
            $inscription->choixForm = $validated['choixForm'];
            $inscription->message = $validated['message'] ?? null;
            $inscription->status = 'En attente'; // Statut par défaut
            
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


    public function inscView()
    {
        $formations = Formation::all();
        return view('admin.newInscView', compact('formations'));
    }

    public function allPayments()
    {
        // $payments = Payment::all();
        // return view('admin.allPayments', compact('payments'));
        return view('admin.allPayments');
    }
    public function newPayment()
    {
        return view('admin.newPayment');
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

}