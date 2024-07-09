<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register (Request $request) {

        $validate = Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'address' => 'required',
            'registration_code' => 'required',
        ]);

        if($validate->fails()) return response()->json(['errors' => $validate->errors()], 400);

        $findCode = Code::where('email', $validate['email'])->where('code', $validate['registration_code'])->first();

        if(!$findCode) return response()->json(['error' => 'Sorry, this code is incorrect'], 400);

        $newUser = User::create([
            'fullname' => $validate['fullname'],
            'email' => $validate['email'],
            'phone' => $validate['phone'],
            'address' => $validate['address'],
            'status' => 'pending'
        ]);

        // Create cache for user
        Cache::put('user_'. $newUser->id, $newUser, 120);

        return response()->json(['message' => 'User has been successfully registered']);
    }
}
