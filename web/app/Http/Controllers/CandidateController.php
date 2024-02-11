<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Employment;
use App\Models\Message;
use App\Models\User;
use App\Services\CandidateService;
use App\Services\ContactService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Rules for hire endpoint
     */
    public const HIRE_RULES = [
        'id' => 'required|numeric|exists:candidates,id',
    ];

    /**
     * @param CandidateService $candidateService
     */
    public function __construct(
        protected CandidateService $candidateService,
    ) {
    }

    /**
     * Get whole list of candidates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function candidates(): \Illuminate\Http\JsonResponse
    {
        $companyId = User::find(Auth::id())->with('company')->first()->id;
        return response()->json([
            'data' => Candidate::all()->mapWithKeys(function ($candidate, $key) use ($companyId) {
                $messagesBetweenUserAndCandidate = Message::where([
                    ['user_id', '=', Auth::id()],
                    ['candidate_id', '=', $candidate->id],
                ]);
                $employmentsByCompany = $candidate->employmentsByCompany($companyId);
                $candidate->messages = [
                    'count' => $messagesBetweenUserAndCandidate->count(),
                    'contacted' => $messagesBetweenUserAndCandidate->count() > 0,
                    'ago' => $messagesBetweenUserAndCandidate->orderBy('created_at', 'desc')->first()?->created_at->diffForHumans() ?? null,
                ];
                $candidate->hired = $employmentsByCompany->count() > 0;

                return [$candidate->id => $candidate];
            })->toArray(),
            'auth' => Auth::check(),
        ]);
    }

    /**
     * Get endpoint for one candidate
     *
     * @param string $id
     * @return mixed
     */
    public function candidate(string $id): mixed
    {
        $companyId = User::find(Auth::id())->with('company')->first()->id;
        return Candidate::where([['id', '=', $id]])->get()->map(function ($candidate, $key) use ($companyId) {
            $messagesBetweenUserAndCandidate = Message::where([
                ['user_id', '=', Auth::id()],
                ['candidate_id', '=', $candidate->id],
            ]);
            $employmentsByCompany = $candidate->employmentsByCompany($companyId);
            $candidate->messages = [
                'count' => $messagesBetweenUserAndCandidate->count(),
                'contacted' => $messagesBetweenUserAndCandidate->count() > 0,
                'ago' => $messagesBetweenUserAndCandidate->orderBy('created_at', 'desc')->first()?->created_at->diffForHumans() ?? null,
            ];
            $candidate->hired = $employmentsByCompany->count() > 0;

            return $candidate;
        })->first();
    }

    /**
     * Hire post endpoint that will make the Employment possible
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|true[]
     */
    public function hire(Request $request): array|\Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), self::HIRE_RULES);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            return $this->candidateService->hire($request->get('id'));
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
