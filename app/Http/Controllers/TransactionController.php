<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function store(Request $request, TransferService $transferService)
    {
        $validatedData = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $transferService->transfer(
            $request->user()->id,
            $validatedData['recipient_id'],
            $validatedData['amount']
        );

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
