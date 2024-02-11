<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Message;
use App\Models\User;
use App\Services\ContactService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(): \Illuminate\Http\JsonResponse
    {
        $user = User::find(1);
        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();
            $token = $user->createToken('access_token');
            return response()->json(
                [
                'me' => User::with('company')->find(Auth::id()),
                'auth' => Auth::check(),
                'token' => $token->plainTextToken, // or plainTextToken
                ],
                200
            );
        } else {
            return response()->json(
                [
                'data' => 'Unauthorized Access'
                ],
                401
            );
        }
    }

    /**
     * @return array
     */
    public function me(): array
    {
        return [
            'data' => User::with('companyWithWallet')->find(Auth::id()),
        ];
    }
}
