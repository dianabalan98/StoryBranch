<?php

namespace App\Http\Controllers;

use App\User;
use App\Story;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// pentru stocarea de imagini (fisiere din form)
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class UserController extends Controller
{
    public function getProfile($author_id) {
        $author = User::find($author_id);

        return view('users.authorProfile')->with('author', $author);
    }


    public function edit()
    {
        // pt cazul in care avem eroare la gasirea de id pur si simplu ne intoarcem la pagina anterioara
        if(Auth::user()) {

            $user = User::find(Auth::user()->id);

            if($user) {

                return view('users.edit')->with('user', $user);
            }
            else {
                return redirect()->back();
            }  
        }

        else {
            return redirect()->back();
        }
    }

    
    public function update(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if($user) {
            // validare inainte de actualizare
            // posibil sa adaug mai multe validari in viitor

            if($user->email === $request['email'] and $user->username === $request['username']) {
                $this->validate($request, [
                    'username' => 'required|exists:users|max:100',
                    'email' => 'required|email|exists:users|max:255',
                    'bio' => 'max:700',
                    'avatar' => 'mimes:jpeg,png'
                ]);
            }
            elseif($user->email === $request['email']) {
                $this->validate($request, [
                    'username' => 'required|unique:users|max:100',
                    'email' => 'required|email|exists:users|max:255',
                    'bio' => 'max:700',
                    'avatar' => 'mimes:jpeg,png'
                ]);

            }
            elseif ($user->username === $request['username']) {
                $this->validate($request, [
                    'username' => 'required|exists:users|max:100',
                    'email' => 'required|email|unique:users|max:255',
                    'bio' => 'max:700',
                    'avatar' => 'mimes:jpeg,png'
                ]);
            }
            else {
                $this->validate($request, [
                    'username' => 'required|unique:users|max:100',
                    'email' => 'required|email|unique:users|max:255',
                    'bio' => 'max:700',
                    'avatar' => 'mimes:jpeg,png'
                ]);
            }
            
    
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->bio = $request['bio'];

            // partea cu imaginea (trebuie sa concatenez nume + extensie)
            // folosesc Storage si File facade pt a stoca imaginile uploadate in folderul public al aplicatiei
            if ($request->avatar) {

                $avatar = $request->file('avatar');
                $extension = $avatar->getClientOriginalExtension(); // getting avatar image extension

                $avatar_name =time().'.'.$extension;
                $avatar->move('uploads/user', $avatar_name);

                $user->avatar = $avatar_name;

            }
            

            $user->save();

            $request->session()->flash('success', 'Your details have been successfully updated!');

            return  redirect()->back();
        }
        else {
            return redirect()->back();
        }
    }


    public function checkEmail(Request $request) {
        $user = User::find(Auth::user()->id);
        if($user->email != $request->email) {
            $duplicate = User::where('email', '=', $request->email)->first();
            if($duplicate) return "error";
        }
        return;
    }

    public function checkUsername(Request $request) {
        $user = User::find(Auth::user()->id);
        if($user->username != $request->username) {
            $duplicate = User::where('username', '=', $request->username)->first();
            if($duplicate) return "error";
        }
        return;
    }
    
}
