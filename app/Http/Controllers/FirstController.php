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
            $insc->save();
            return redirect()->back();
        } else {
            $insc->name = $req->name;
            $insc->email = $req->email;
            $insc->phone = $req->phone;
            $insc->address = $req->address;
            $insc->message = $req->message;
            $insc->choixForm = $req->choixForm;
            $insc->montant = '14 500 FCFA';
            $insc->save();
            return redirect()->back();
        }
        

    }
    

}
