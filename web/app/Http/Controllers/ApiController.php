<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login()
    {
        $user = User::find(1);
        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();
            $token = $user->createToken('access_token');
            return response()->json([
                'me' => User::with('company')->find(Auth::id()),
                'auth' => Auth::check(),
                'token' => $token->plainTextToken, // or plainTextToken
            ], 200);
        } else {
            return response()->json([
                'data' => 'Unauthorized Access'
            ], 401);
        }
    }

    public function me()
    {
        return [
            'data' => User::with('company')->find(Auth::id()),
        ];
    }

    public function candidate()
    {
        $candidates = Candidate::all();
        return [
            'data' => $candidates,
            'auth' => Auth::check(),
        ];
    }
}
