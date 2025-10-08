<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('a user can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'madewithlaravel'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => $password,
    ]);
    $this->assertAuthenticated();
    $response->assertNoContent();
});

test('a user cannot login with invalid credentials', function () {
    $response = $this->post('/login', [
        'email' => 'jpt@example.com',
        'password' => 'invalidpassword',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Invalid credentials',
    ]);
});

//test('users can logout', function () {
//    Sanctum::actingAs($user = User::factory()->create());
//    $response = $this->post('logout');
//
//    $this->assertGuest();
//    $response->assertNoContent();
//});

test('an authenticated user can fetch their information', function () {
    Sanctum::actingAs($user = User::factory()->create());

    $response = $this->getJson('/api/whoami');

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
