<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertRedirect('/');
});

test('new users can register', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'cuit' => '20-12345678-9',
    ]);

    $this->assertGuest();
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'autorizado' => false,
    ]);
    $response->assertRedirect('/');
    Notification::assertNothingSent();
});

test('legacy registration can be received without an email address', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Legacy Client',
        'password' => 'password',
        'password_confirmation' => 'password',
        'cuit' => '20-12345678-9',
    ])->assertRedirect('/')->assertSessionHasNoErrors();

    $this->assertGuest();
    $this->assertDatabaseHas('users', [
        'name' => 'Legacy Client',
        'email' => null,
        'autorizado' => false,
    ]);
    Notification::assertNothingSent();
});
