<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;



class UserController extends Controller
{
    public function checkSession()
    {
        // check if the session is started
        if (session()->isStarted()) {
            
            $userId = session('user_id');
            $userName = session('user_name');

            return "Session is started. User ID: " . $userId . ", User Name: " . $userName;
        } else {
         
            return "Session is not started.";
        }
    }

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
public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Generate a new access token
            $token = $user->createToken('MyApp')->plainTextToken;


            // return the usee name along with the token
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

    public function updateProfile(Request $request)
    {
        try {

            if (auth()->check()) {
                Log::info('User authenticated:', ['id' => auth()->id(), 'name' => auth()->user()->name]);
            } else {
                Log::info('User not authenticated');
            }
    
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'contactNumber' => 'required',
                'email' => 'required|email|unique:users,email,' . auth()->user()->id, // Exclude the current user's email from unique validation
                'address' => 'required',
                'description' => 'nullable|string',
            ]);
    
            // get authenticated user
            $user = auth()->user();
    
           
            Log::info('Received request to update profile', $validatedData);
          
    
            // update user profile data
            $user->name = $validatedData['name'];
            $user->contactNumber = $validatedData['contactNumber'];
            $user->email = $validatedData['email'];
            $user->address = $validatedData['address'];
            $user->description = $validatedData['description'];
            $user->save();
    
            return response()->json(['message' => 'Profile updated successfully'], 200);
        } catch (\Exception $e) {
            
            Log::error('Error updating profile:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
