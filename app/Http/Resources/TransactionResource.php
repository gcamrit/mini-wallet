<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isSent = $this->resource->sender_id === $user->id;

        $counterparty = $isSent ? $this->resource->receiver : $this->resource->sender;

        return [
            'id' => $this->resource->id,
            'type' => $isSent ? 'SENT' : 'RECEIVED',
            'party' => $counterparty
                ? [
                    'id' => $counterparty->id,
                    'name' => $counterparty->name,
                ]
                : null,
            'amount' => $this->resource->amount,
            'created_at' => $this->resource->created_at,
        ];
    }
}
