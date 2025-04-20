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
        // $image = $req->image;
        // $nomImage = time().".".getClientoriginalExtension();
        // $image = $req->image;
        // $nomImage = time().".".$getClientOriginalExtention();
        // $fileName = time().$req->file('image')->getClientOriginalName();
        // $path = $req->file('image')->storeAs('imgsForm', $fileName, 'public');
        // $form["image"] = '/storage/' . $path;
        // $path = $req->image->move('imgsForm',$fileName,'public');
        // $form->image = $req->image->move('imgsForm',$fileName);
        
        // $form = new Formation();
        // $image = $req->image;
        // $fileName = time().$req->file('image')->getClientOriginalName();
        // $path = $req->image->move('imgsForm',$fileName,'public');
        // $form["image"] = $path;
        // $form->libForm = $req->libForm;
        // $result = $form->save();

        // // Validation
        // $req->validate([
        //     'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        // ]);

        // // Récupérer l'image envoyée via le champ "image"
        // $image = $req->image;

        // // Générer un nom unique pour l'image
        // $imageName = time() . '_' . $image->getClientOriginalName();

        // // Déplacer l'image dans public/imgForms
        // $image->move('imgForms', $imageName);

        // // Générer l'URL publique de l'image
        // $imagePath = asset('imgForms/' . $imageName);

        // $form->image = $imageName;
        // $form->libForm = $req->libForm;

        // $result = $form->save();
        // Validation
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
        $imagePath = '/storage/imgForms/' . $imageName;

        $form->image = $imagePath;
        $form->libForm = $req->libForm;
        $form->desc = $req->desc;

        $result = $form->save();

        if ($result) {
            return redirect()->back();
        } else {
            return view("home.uHome");
        }
    }
}
