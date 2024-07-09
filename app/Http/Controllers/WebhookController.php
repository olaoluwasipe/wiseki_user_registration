<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    public function updateStatus (Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'status' => 'required|in:active,rejected'
        ]);

        if($validate->fails()) return response()->json(['error' => $validate->errors()], 400);


        $findUser = User::where('email', $validate['email'])->first();

        if(!$findUser) return response()->json(['error' => 'User not found'], 404);

        $findUser->update([
            'status' => $validate['status'],
        ]);

        return response()->json(['message' => 'User status has been updated successfully']);
    }
}
