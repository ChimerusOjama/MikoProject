<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function newForm(){
        return view('admin.newForm');
    }

    public function storeForm(Request $req){
        $form = new Formation();

        $req->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Récupérer l'image envoyée via le champ "image"
        $image = $req->image;

        // Générer un nom unique pour l'image
        $imageName = time() . '_' . $image->getClientOriginalName();

        // Déplacer l'image dans public/imgForms
        $image->move('imgForms', $imageName);

        // Générer l'URL publique de l'image
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
}
