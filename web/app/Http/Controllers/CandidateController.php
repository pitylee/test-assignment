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

class CandidateController extends Controller
{
    public function candidates()
    {
        return response()->json([
            'data' => Candidate::limit(2)->get()->mapWithKeys(function ($candidate, $key) {
                $messagesBetweenUserAndCandidate = Message::where([
                    ['user_id', '=', Auth::id()],
                    ['candidate_id', '=', $candidate->id],
                ]);
                $candidate->messages = [
                    'count' => $messagesBetweenUserAndCandidate->count(),
                    'contacted' => $messagesBetweenUserAndCandidate->count() > 0,
                    'ago' => $messagesBetweenUserAndCandidate->orderBy('created_at', 'desc')->first()?->created_at->diffForHumans() ?? null,
                ];

                return [$candidate->id => $candidate];
            })->toArray(),
            'auth' => Auth::check(),
        ]);
    }

    public function candidate(string $id)
    {
        return Candidate::where([['id', '=', $id]])->get()->map(function ($candidate, $key) {
            $messagesBetweenUserAndCandidate = Message::where([
                ['user_id', '=', Auth::id()],
                ['candidate_id', '=', $candidate->id],
            ]);
            $candidate->messages = [
                'count' => $messagesBetweenUserAndCandidate->count(),
                'contacted' => $messagesBetweenUserAndCandidate->count() > 0,
                'ago' => $messagesBetweenUserAndCandidate->orderBy('created_at', 'desc')->first()?->created_at->diffForHumans() ?? null,
            ];

            return $candidate;
        })->first();
    }
}
