<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\Request;

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

    public function storeForm(Request $req){
        $form = new Formation();

        $req->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = $req->image;
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move('imgForms', $imageName);
        $imagePath = 'imgForms/' . $imageName;

        $form->image = $imagePath;
        $form->libForm = $req->libForm;
        $form->desc = $req->desc;

        $result = $form->save();

        if ($result) {
            return redirect()->back()->with('success', 'La formation a été crée avec succès');
        } else {
            return view("home.uHome");
        }
    }

    public function supForm($id){
        $delInsc = Formation::find($id);
        $delInsc->delete();
        return redirect()->back()->with('message2', 'La formation a été supprimée avec succès');
    }

    public function updateView($id){
        $form = Formation::find($id);
        return view('admin.updateForm', compact('form'));
    }

    public function updateForm(Request $req, $id){

        $form = Formation::find($id);
        $req->validate([
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $form->libForm = $req->libForm;
        $form->desc = $req->desc;

        // Récupérer l'image envoyée via le champ "image"
        $image = $req->image;

        if($image){
            // Générer un nom unique pour l'image
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Déplacer l'image dans public/imgForms
            $image->move('imgForms', $imageName);

            // Générer l'URL publique de l'image
            $imagePath = 'imgForms/' . $imageName;

            $form->image = $imagePath;
        }


        $result = $form->save();

        if ($result) {
            return redirect()->back()->with('success', 'La formation a été mise à jour avec succès');
        } else {
            return view("home.uHome");
        }





    }

    public function reserveView(){
        $allInsc = Inscription::all();
        return view('admin.fromUserReserve', compact('allInsc'));
    }

    public function accepterRes($id){
        $res = Inscription::find($id);
        $stat = $res->status;
        if ($stat == 'Accepté') {
            return redirect()->back()->with('message', 'La demande a déjà été acceptée');
        } else {
            $res->status = 'Accepté';
            $res->save();
            return redirect()->back()->with('message2', 'Vous avez accepté la demande');
        }  
    }

    public function rejeterRes($id){
        $res = Inscription::find($id);
        $res->status = 'Rejeté';
        $res->save();
        return redirect()->back()->with('etat', 'success');
    }
}
