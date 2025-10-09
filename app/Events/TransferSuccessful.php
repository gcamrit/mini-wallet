<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferSuccessful implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly Transaction $transaction)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->transaction->sender_id. '.transactions'),
            new PrivateChannel('user.' . $this->transaction->receiver_id. '.transactions'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'transaction' => [
                'id' => $this->transaction->id,
                'amount' => $this->transaction->amount,
                'commission_amount' => $this->transaction->commission_fee,
                'sender' => [
                    'id' => $this->transaction->sender->id,
                    'name' => $this->transaction->sender->name,
                ],
                'receiver' => [
                    'id' => $this->transaction->receiver->id,
                    'name' => $this->transaction->receiver->name,
                ],
                'created_at' => $this->transaction->created_at
            ],
        ];
    }
}
