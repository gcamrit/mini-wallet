<?php

use App\Events\TransferSuccessful;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

pest()->use(\Illuminate\Foundation\Testing\RefreshDatabase::class);


it('can list transactions for the authenticated user', function () {
    Sanctum::actingAs($user = User::factory()->create(['balance' => 1000]));
    $anotherUser = User::factory()->create();

    Transaction::factory()->create(['sender_id' => $user->id, 'receiver_id' => $anotherUser->id, 'amount' => 100]);
    Transaction::factory()->create(['sender_id' => $anotherUser->id, 'receiver_id' => $user->id, 'amount' => 50]);
    Transaction::factory()->create(['sender_id' => $anotherUser->id, 'receiver_id' => $anotherUser->id, 'amount' => 200]);

    getJson('/api/transactions')
        ->assertStatus(200)
        ->assertJson(['meta' => ['balance' => 1000]]);
});

it('can create a transaction', function () {
    Sanctum::actingAs($sender = User::factory()->create(['balance' => 1000]));
    $receiver = User::factory()->create();

    Event::fake();

    postJson('/api/transactions', [
        'recipient_id' => $receiver->id,
        'amount' => 100,
    ])->assertStatus(201);

    Event::assertDispatched(TransferSuccessful::class);

    assertDatabaseHas('transactions', [
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'amount' => 100,
        'commission_fee' => 1.5
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $sender->id,
        'balance' => 898.5
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $receiver->id,
        'balance' => 1100
    ]);
});

it('cannot create a transaction with insufficient balance', function () {
    Sanctum::actingAs(User::factory()->create(['balance' => 100]));
    $receiver = User::factory()->create();

    postJson('/api/transactions', [
        'receiver_id' => $receiver->id,
        'amount' => 100,
    ])->assertStatus(422);
});

it('cannot transfer money to himself', function () {
    Sanctum::actingAs($user = User::factory()->create(['balance' => 1000]));

    postJson('/api/transactions', [
        'recipient_id' => $user->id,
        'amount' => 100,
    ])->assertStatus(422);
});
