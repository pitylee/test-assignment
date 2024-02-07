<?php

namespace App\Services;

use App\Models\Wallet;
use Mockery\Exception;

final class WalletService
{
    public function __construct()
    {
    }

    public function chargeAmount(int $amount, Wallet $wallet): bool
    {
        if ($wallet->coins < $amount) {
            throw new Exception('There is not enough coins in this wallet!');
        }

        try {
            $wallet->coins = $wallet->coins + $amount;
            $wallet->save();
            return true;
        } catch (\Throwable $exception) {
            throw new $exception();
        }
    }
}
