<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function getUsers (Request $request) {
        $status = $request->query('status', 'all');

        $cacheKey = 'users_' . $status ;
        $users = Cache::remember($cacheKey, 60, function () use ($status) {
            if($status === 'all') {
                return User::all();
            }

            return User::where('status', $status)->get();
        });

        return response()->json($users);
    }
}
