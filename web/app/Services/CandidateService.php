<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Employment;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class CandidateService
{
    public const AMOUNT = -5;

    public function __construct(
        private WalletService $walletService,
    )
    {
    }

    public function hire(int $candidateId): array
    {
        $userId = Auth::id();
        $candidate = Candidate::find($candidateId);
        $user = User::find($userId);
        $company = $user->company()->first();
        $charged = false;
        $metadata = [];

        try {
            $alreadyHired = Employment::where([
                ['candidate_id', '=', $candidate->id],
                ['company_id', '=', $company->id],
            ]);

            if ($alreadyHired->count()) {
                throw new \Exception('This candidate is already hired at this company!');
            }

            $charged = $this->walletService->chargeAmount(-self::AMOUNT, $company->wallet, $metadata);

            $employment = new Employment();
            $employment->metadata = $metadata;

            $employment->company()->associate($company);
            $employment->user()->associate($user);
            $employment->candidate()->associate($candidate);
            $employment->save();

            return ['success' => true];
        } catch (\Throwable $exception) {
            if ($charged) {
                $metadata['revert'] = true;
                $this->walletService->chargeAmount(+self::AMOUNT, $company->wallet, $metadata);
            }
            throw $exception;
        }
    }
}
