<?php

namespace App\Http\Controllers;

use App\Mail\infoMail;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FirstController extends Controller
{
    //
    public function redirect(){
        if(Auth::id()){
            $usertype = Auth()->user()->usertype;
            if($usertype === 'user') {
                $forms = Formation::all();
                return view('home.uHome', compact('forms'));
            } elseif($usertype === 'admin') {
                return view('admin.index');
            } else {
                return redirect()->back();
            }
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
            $forms = Formation::all();
            return view('home.uHome', compact('forms'));
        }
    }

    public function index(){
        if (Auth::id()) {
            return redirect('home');
        } else {
            $forms = Formation::all();
            return view('home.uHome', compact('forms'));
        }
    }

    public function formListing(){
        $forms = Formation::all();
        return view('home.listing', compact('forms'));
    }

    public function formSingle($id){
        $oneForm = Formation::find($id);
        return view('home.singleForm', compact('oneForm'));
    }

    public function formInsc(Request $req){
        // Validation des données d'inscription
        $req->validate([
            'choixForm' => 'required',
            'message' => 'nullable|string|max:500',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:18',
            'address' => 'required|string|max:255',
        ]);
        // Vérification si l'utilisateur est connecté
        // Si l'utilisateur est connecté, on utilise Auth::id() pour obtenir son ID
        if (Auth::id()) {
            $existing = Inscription::where('user_id', Auth::id())
                ->where('choixForm', $req->choixForm)
                ->first();

            if ($existing) {
                return redirect()->back()->with('warning', 'Vous êtes déjà inscrit à cette formation.');
            }

            $insc = new Inscription();
            $insc->user_id = Auth::id();
            $insc->name = Auth::user()->name;
            $insc->email = Auth::user()->email;
            $insc->phone = Auth::user()->phone;
            $insc->address = Auth::user()->address;
            $insc->message = $req->message;
            $insc->choixForm = $req->choixForm;
            $insc->montant = '14 500 FCFA';
            $insc->status = 'En attente';
            $insc->save();
            Mail::to(Auth::user()->email)->send(new infoMail());
            return redirect()->back()->with('success', 'Votre demande a été reçue avec succès.');
        }else{
            return redirect()->back()->with('warning', 'Vous devez être connecté pour soumettre une inscription.');
        }

        // Utilisateur non connecté (on utilise l'email comme identifiant)
        // $existing = Inscription::where('email', $req->email)
        //     ->where('choixForm', $req->choixForm)
        //     ->first();

        // if ($existing) {
        //     return redirect()->back()->with('warning', 'Vous êtes déjà inscrit à cette formation.');
        // }

        // $insc = new Inscription();
        // $insc->name = $req->name;
        // $insc->email = $req->email;
        // $insc->phone = $req->phone;
        // $insc->address = $req->address;
        // $insc->message = $req->message;
        // $insc->choixForm = $req->choixForm;
        // $insc->montant = '14 500 FCFA';
        // $insc->status = 'En cours';
        // $insc->save();

        // // Envoi d'un email de confirmation (si nécessaire)
        // // Ship the order...
    
        // Mail::to($req->email)->send(new infoMail());
        
        // return redirect()->back()->with('success', 'Votre demande a été reçue avec succès.');

    }

    public function uAdmin(){
        if (Auth::id()) {

            $userName = Auth::user()->name;
            $inscShow = Inscription::where('name', $userName)->get();
            return view('admin2.index', compact('inscShow'));

        } else {
            return redirect()->back();
        }
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

public function annulerRes($id)
{
    $delInsc = Inscription::findOrFail($id);
    $delInsc->delete();

    return redirect()->back()->with([
        'message' => 'Votre réservation a bien été annulée.',
        'type' => 'success'
    ]);
}


    // public function annulerRes($id){
    //     $delInsc = Inscription::find($id);
    //     $delInsc->delete();
    //     return redirect()->back();
    // }

    public function aboutView(){
        return view('home.about');
    }

    public function uProfile(){
        if (Auth::id()) {
            $user = Auth::user();
            return view('home.uProfile', compact('user'));
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

    

}
