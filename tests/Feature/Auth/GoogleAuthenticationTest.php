<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('users are redirected to google for authentication', function () {
    Socialite::fake('google');

    $response = $this->get(route('auth.google.redirect', ['source' => 'login']));

    $response->assertRedirect();
});

test('google callback creates a new user on first login', function () {
    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-123',
        'name' => 'Google User',
        'email' => 'google-user@example.com',
    ]));

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'google-user@example.com',
        'google_id' => 'google-123',
        'name' => 'Google User',
    ]);
});

test('google callback links existing users by email', function () {
    $user = User::factory()->create([
        'email' => 'existing-user@example.com',
    ]);

    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-456',
        'name' => 'Existing User',
        'email' => 'existing-user@example.com',
    ]));

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user->fresh());
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'existing-user@example.com',
        'google_id' => 'google-456',
    ]);
});
