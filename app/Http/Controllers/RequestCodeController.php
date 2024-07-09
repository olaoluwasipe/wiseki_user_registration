<?php

namespace App\Http\Controllers;

use App\Jobs\SendCode;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestCodeController extends Controller
{
    public function requestCode (Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if($validate->fails()) return response()->json(['error' => $validate->errors()], 400);


        $code = random_int(000000, 999999);

        $alreadyExists = Code::where('email', $request->email)->first();

        if($alreadyExists) {
            $alreadyExists->update([
                'code' => $code,
                'created_at' => now()
            ]);

            SendCode::dispatch( $request->email, $code);

            return response()->json(['message' => 'A new code has been sent successfully']);
        }

        Code::create([
            'email' =>  $request->email,
            'code' => $code,
            'created_at' => now()
        ]);

        // Send email here
        SendCode::dispatch( $request->email, $code);

        return response()->json(['message' => 'Code created successfully']);

    }
}
