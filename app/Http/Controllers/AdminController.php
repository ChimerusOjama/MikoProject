<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    //
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
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'titre' => 'required|string|max:255',
            'description_courte' => 'required|string',
            'categorie' => 'required|string|max:50|in:informatique,gestion,langues',
            'niveau' => 'required|string|max:20|in:debutant,intermediaire,avance',
            'prix' => 'required|numeric|min:0|max:100000',
            'status' => 'required|string|in:publiee,brouillon,archivee',
            'duree_mois' => 'required|integer|min:1|max:24',
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

    public function inscsView(){
        $allInsc = Inscription::with('formation')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('admin.fromUserInscriptions', compact('allInsc'));
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
}
