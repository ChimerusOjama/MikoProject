<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

            // return redirect('home', compact('forms'));
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
        // return view('home.singleForm', compact('forms'));
    }

    public function formInsc(Request $req){
        $insc = new Inscription();

        if (Auth::id()) {
            $insc->user_id = Auth::user()->id;
            $insc->name = Auth::user()->name;
            $insc->email = Auth::user()->email;
            $insc->phone = Auth::user()->phone;
            $insc->address = $req->address;
            $insc->message = $req->message;
            $insc->choixForm = $req->choixForm;
            $insc->montant = '14 500 FCFA';
            $insc->status = 'En cours';
            $insc->save();
            return redirect()->back()->with('success', 'Votre demanade a été reçu avec succès');
        } else {
            $insc->name = $req->name;
            $insc->email = $req->email;
            $insc->phone = $req->phone;
            $insc->address = $req->address;
            $insc->message = $req->message;
            $insc->choixForm = $req->choixForm;
            $insc->montant = '14 500 FCFA';
            $insc->status = 'En cours';
            $insc->save();
            return redirect()->back()->with('success', 'Votre demanade a été reçu avec succès');
        } 
    }

    public function uAdmin(){
        if (Auth::id()) {

            $userName = Auth::user()->name;
            $inscShow = Inscription::where('name', $userName)->get();
            return view('admin2.index', compact('inscShow'));

            // $user_id = Auth::id();
            // $inscById = Inscription::where('user_id', $user_id)->get();
            // return view('admin2.index', compact('inscById'));

            // if ($user_id == 'null') {
            //     $userName = Auth::user()->name;
            //     $inscById = Inscription::where('name', $userName)->get();
            //     return view('admin2.index', compact('inscById'));
            // } else {
            //     $user_id = Auth::id();
            //     $inscById = Inscription::where('user_id', $user_id)->get();
            //     return view('admin2.index', compact('inscById'));
            // }

        } else {
            return redirect()->back();
        }
    }

    public function annulerRes($id){
        $delInsc = Inscription::find($id);
        $delInsc->delete();
        return redirect()->back();
    }

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
