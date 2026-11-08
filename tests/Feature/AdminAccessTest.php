<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('denies customer access to admin pages', function () {
    $user = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/admin/rentals');

    $response->assertForbidden();
});

it('allows admin access to admin pages', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this
        ->actingAs($admin)
        ->get('/admin/rentals');

    $response->assertOk();
});