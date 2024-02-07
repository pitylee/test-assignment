<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use App\Services\ContactService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public const CONTACT_RULES = [
        'id' => 'required|numeric|exists:candidates,id',
        'subject' => 'required|string',
        'message' => 'required|string',
    ];

    public function __construct(
        protected ContactService $contactService,
    )
    {
    }

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

    public function me()
    {
        return [
            'data' => User::with('company')->find(Auth::id()),
        ];
    }

    public function candidate()
    {
        $candidates = Candidate::limit(2)->get();
        return [
            'data' => $candidates,
            'auth' => Auth::check(),
        ];
    }

    /**
     * @throws \Exception
     */
    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), self::CONTACT_RULES);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->contactService->sendEmail(
            $request->input('id'),
            $request->input('message'),
            subject: $request->input('subject'),
        );
    }
}
