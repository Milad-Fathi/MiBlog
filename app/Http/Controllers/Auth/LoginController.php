<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LoginController extends Controller
{
//     public function login(Request $request)
// {
//     $credentials = $request->only('email', 'password');

//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();
//         if ($user->role !== 'admin') {
//             Auth::logout();
//             return redirect()->route('login')->withErrors(['email' => 'Invalid credentials.']);
//         }

//         return redirect()->intended('dashboard');
//     }

//     return back()->withErrors([
//         'email' => 'The provided credentials do not match our records.',
//     ]);
// }

}
