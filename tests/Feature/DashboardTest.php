<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertOk();
});

test('legacy accounts do not gain a new mandatory email verification requirement', function () {
    $user = User::factory()->unverified()->create(['autorizado' => true]);

    $this->actingAs($user)->get('/dashboard')->assertOk();
});
