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
    public function index(){
        if (Auth::id()) {
            return redirect('home');
        } else {
            $forms = Formation::all();
            return view('index', compact('forms'));
        }
    }
    public function redirect(){
        if(Auth::id()){
            $usertype = Auth()->user()->usertype;
            if($usertype === 'user') {
                $forms = Formation::all();
                return view('index', compact('forms'));
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
            return view('index', compact('forms'));
        }
    }

    public function formListing(){
        $forms = Formation::all();
        return view('listing', compact('forms'));
    }

    public function formSingle($id){
        $oneForm = Formation::find($id);
        return view('inscription', compact('oneForm'));
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

    public function formInsc(Request $req){
        // Validation des données d'inscription
        // $req->validate([
        //     'choixForm' => 'required',
        //     'message' => 'nullable|string|max:500',
        //     'email' => 'required|email|max:255',
        //     'name' => 'required|string|max:255',
        //     'phone' => 'required|string|max:18',
        //     'address' => 'required|string|max:255',
        // ]);
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

    public function uFormation(){
        if (Auth::id()) {
            $userName = Auth::user()->name;
            $inscShow = Inscription::where('name', $userName)->get();
            return view('uAdmin.forms', compact('inscShow'));

        } else {
            return redirect()->back();
        }
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



    // public function annulerRes($id){
    //     $delInsc = Inscription::find($id);
    //     $delInsc->delete();
    //     return redirect()->back();
    // }
}
