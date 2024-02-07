<?php

namespace App\Services;

use App\Mail\ContactEmail;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

final class ContactService
{
    public const AMOUNT = 20;

    public function __construct(
        private WalletService $walletService,
    )
    {
    }

    public function sendEmail(int $id, string $message, ?string $subject = null): array
    {
        $candidate = Candidate::find($id);
        $emails = [
            $candidate->email,
        ];
        $company = User::find(Auth::id())->company()->first();

        try {
            $this->walletService->chargeAmount(-self::AMOUNT, $company->wallet);

            Mail::to($emails)
                ->send(new ContactEmail($message, $candidate, $subject));

            return ['success' => true];
        } catch (\Throwable $exception) {
            $this->walletService->chargeAmount(+self::AMOUNT, $company->wallet);
            throw new $exception();
        }
    }
}
