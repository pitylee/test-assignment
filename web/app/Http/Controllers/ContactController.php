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

class ContactController extends Controller
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
