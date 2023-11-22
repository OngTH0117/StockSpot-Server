<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getMessage()
    {
        $message = 'testing 123'; 
        return response()->json(['message' => $message]);
    }
}
