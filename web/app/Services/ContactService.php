<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class ContactService
{
    /**
     *  Amount that will be applied on the wallet
     */
    public const AMOUNT = 5;

    /**
     * @param WalletService $walletService
     */
    public function __construct(
        private WalletService $walletService,
    ) {
    }

    /**
     * Will create a Message entry for given candidate by company, initialized by user.
     *
     * The Model will handle the email sending to the candidate email via ModelObserver
     *
     * If it succeeds, subtracts AMOUNT coins from wallet, if fails puts back into the wallet.
     *
     * Returns success boolean or throws Exception that will be handled elsewhere.
     *
     * @param int $id
     * @param string $message
     * @param string|null $subject
     * @return true[]
     * @throws \Throwable
     */
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
