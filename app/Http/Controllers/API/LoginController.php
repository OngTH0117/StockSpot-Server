<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // generate new access token
            $token = $user->createToken('MyApp')->plainTextToken;
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);


            // return the user name with the token
            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        } else {
           
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }
}