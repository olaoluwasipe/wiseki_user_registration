<?php

namespace App\Http\Controllers;

use App\Jobs\SendCode;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestCodeController extends Controller
{
    public function requestCode (Request $request) {
        $validate = $request->validate([
            'email' => 'required|email|unique:users'
        ]);

        // if($validate->fails()) return response()->json(['error' => $validate->errors()], 400);

        $code = rand(000000, 999999);

        $alreadyExists = Code::where('email', $validate['email'])->first();

        if($alreadyExists) {
            $alreadyExists->update([
                'code' => $code,
                'created_at' => now()
            ]);

            SendCode::dispatch($validate['email'], $code);

            return response()->json(['message' => 'Code updated successfully']);
        }

        Code::create([
            'email' => $validate['email'],
            'code' => $code,
            'created_at' => now()
        ]);

        // Send email here
        SendCode::dispatch($validate['email'], $code);

        return response()->json(['message' => 'Code created successfully']);

    }
}
