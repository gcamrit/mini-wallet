<?php

namespace App\Services;

use App\Events\TransferSuccessful;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\SameUserTransferException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferService
{
    const COMMISSION_RATE = 0.015;

    public function transfer($senderId, $receiverId, $amount)
    {
        DB::transaction(function () use ($senderId, $receiverId, $amount) {
            if ((int)$senderId === (int)$receiverId) {
                throw new SameUserTransferException('You cannot transfer money to yourself.');
            }

            $commissionAmount = round($amount * self::COMMISSION_RATE, 2);
            $totalDebitAmount = $amount + $commissionAmount;

            $sender = User::query()->lockForUpdate()->findOrFail($senderId);
            $receiver = User::query()->lockForUpdate()->findOrFail($receiverId);

            if ($sender->balance < $totalDebitAmount) {
                throw new InsufficientBalanceException('You don\'t have enough balance');
            }

            $transaction = new Transaction();
            $transaction->sender()->associate($sender);
            $transaction->receiver()->associate($receiver);
            $transaction->amount = $amount;
            $transaction->commission_fee = $commissionAmount;
            $transaction->save();

            $sender->update(['balance' => $sender->balance - $totalDebitAmount]);
            $receiver->update(['balance' => $receiver->balance + $amount]);
            event(new TransferSuccessful($transaction));
        });
    }
}
