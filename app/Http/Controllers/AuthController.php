<?php

namespace App\Http\Controllers;

use App\Models\user; 

use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    public function show($id){
        

        $user = user::findOrFail($id);

        return view('auth.show', ['user' => $user]);
    }

    public function create(){
        return view('auth.create');
    }

    public function store(){

        $user = new user();

        $user->name = request('name');
        $user->email = request('email');
        $user->password = request('password');

        $user->save();
        
        return redirect('/home');
    }
}
