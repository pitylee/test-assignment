<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class ContactService
{
    public const AMOUNT = 5;

    public function __construct(
        private WalletService $walletService,
    )
    {
    }

    public function sendEmail(int $id, string $message, ?string $subject = null): array
    {
        $user = User::find(Auth::id())->first();
        $company = $user->company()->with('wallet')->first();
        $candidate = Candidate::find($id);
        $charged = false;
        $metadata = [
            'candidate_id' => $candidate->id,
            'type' => 'Contact:SendEmail:Message@ContactEmail',
        ];

        try {
            $charged = $this->walletService->chargeAmount(-self::AMOUNT, $company->wallet, $metadata);

            $messageModel = new Message([
                'subject' => $subject,
                'message' => $message,
            ]);
            $messageModel->user()->associate($user);
            $messageModel->company()->associate($company);
            $messageModel->candidate()->associate($candidate);
            $messageModel->save();

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
