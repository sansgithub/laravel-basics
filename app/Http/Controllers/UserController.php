<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\User;
class UserController extends Controller
{
    public function getDashboard(){
        if(Auth::user()) {
            return view('dashboard');
        }
        return "Please Login";
    }

    public function postSignUp(Request $request)
    {

        $this->validate($request,[
            'email'=>array('required','email','unique:users'),
            'first_name'=>array('required','max:120'),
            'password'=>array('required','min:4'),
        ]);

        $email=$request['email'];
        $first_name=$request['first_name'];
        $password=bcrypt($request['password']);

        $user=new User();
        $user->email=$email;
        $user->first_name=$first_name;
        $user->password=$password;

        $user->save();

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function postSignIn(Request $request){

        $this->validate($request,[
            'email_for_signin'			=> 'required|email',
            'password_for_signin'		=> 'required',
        ]);

        if(Auth::attempt([
            'email'=>$request['email_for_signin'],
            'password'=>$request['password_for_signin']
        ])){
            return redirect('/dashboard');
        }

        return redirect()->back();
    }
}
