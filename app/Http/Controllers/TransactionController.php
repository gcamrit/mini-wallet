<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController
{
    /**
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return Transaction::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender:id,name', 'receiver:id,name'])
            ->latest()
            ->paginate(25)
            ->toResourceCollection();
    }

}
