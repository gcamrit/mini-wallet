<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('a user can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'madewithlaravel'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'access_token',
        'token_type',
    ]);
});

test('a user cannot login with invalid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'jpt@example.com',
        'password' => 'invalidpassword',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Invalid login details',
    ]);
});

test('a user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout');

    $response->assertSuccessful();
    $response->assertJson([
        'message' => 'Successfully logged out',
    ]);

    $this->assertCount(0, $user->fresh()->tokens);
});

test('an authenticated user can fetch their information', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/whoami');

    $response->assertSuccessful();
    $response->assertJson([
        'id' => $user->getKey(),
        'name' => $user->getAttribute('name'),
        'email' => $user->getAttribute('email'),
    ]);
});

test('an unauthenticated user cannot fetch whoami information', function () {
    $response = $this->getJson('/api/whoami');

    $response->assertStatus(401);
});
