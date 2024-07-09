<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function updateStatus (Request $request) {
        $validate =  $request->validate([
            'email' => 'required|email',
            'status' => 'required|in:active,rejected'
        ]);

        $findUser = User::where('email', $validate['email'])->first();

        if(!$findUser) return response()->json(['error' => 'User not found'], 404);

        $findUser->update([
            'status' => $validate['status'],
        ]);

        return response()->json(['message' => 'User status has been updated successfully']);
    }
}
