<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
{
    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', 
            'email' => 'required|email|unique:users',
            'contactNumber' => 'required',
            'address' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->contactNumber = $validatedData['contactNumber'];
        $user->address = $validatedData['address'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json(['message' => 'Registration successful, Redirect to Login Page'], 200);
    } catch (\Exception $e) {
        
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
       
}
