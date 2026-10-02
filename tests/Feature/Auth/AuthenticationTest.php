<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create(['autorizado' => true]);

    $response = $this->post('/login', [
        'name' => $user->name,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/privada/productos');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create(['autorizado' => true]);

    $this->post('/login', [
        'name' => $user->name,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('authorized legacy users can authenticate with a username or email', function (string $field) {
    $user = User::factory()->unverified()->create(['autorizado' => true]);

    $response = $this->post('/login', [
        $field => $field === 'email' ? $user->email : $user->name,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/privada/productos');
})->with(['usuario', 'name', 'email']);

test('accounts awaiting authorization cannot authenticate', function () {
    $user = User::factory()->create(['autorizado' => false]);

    $this->post('/login', [
        'usuario' => $user->name,
        'password' => 'password',
    ])->assertSessionHasErrors('login');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
