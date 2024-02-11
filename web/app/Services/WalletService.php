<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Wallet;
use App\Models\WalletTransactions;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

final class WalletService
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * Will subtract the amount of coins from Wallet's coins, both given as parameter; keeps track of previous and current coins standings.
     *
     * Possible to save metadata along.
     *
     * The transaction gets saved into WalletTransactions.
     *
     * @param int $amount
     * @param Wallet $wallet
     * @param array $metadata
     * @return bool
     * @throws \Throwable
     */
    public function chargeAmount(int $amount, Wallet $wallet, array $metadata = []): bool
    {
        $previousAmount = $wallet->coins;
        $newAmount = $previousAmount + $amount;
        if ($newAmount < 0) {
            throw new Exception('There is not enough coins in this wallet!');
        }

        try {
            $wallet->coins = $newAmount;
            $wallet->save();

            $transaction = new WalletTransactions([
                'amount' => $amount,
                'coins_current' => $newAmount,
                'coins_previous' => $previousAmount,
                'metadata' => $metadata,
            ]);
            $transaction->wallet()->associate($wallet);
            $transaction->user()->associate(Auth::user());
            $transaction->save();

            return true;
        } catch (\Throwable $exception) {
            throw new $exception();
        }
    }
}
