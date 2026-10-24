<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Service class for Interactive Google OAuth Simulation & Customer Authentication.
 */
class GoogleAuthService
{
    /**
     * Retrieve pre-configured interactive demo Google accounts.
     *
     * @return array<int, array{name: string, email: string, avatar: string, subtitle: string}>
     */
    public function getPresetAccounts(): array
    {
        return [
            [
                'name' => 'Alya Zahiyyah',
                'email' => 'alya.customer@gmail.com',
                'avatar' => 'https://ui-avatars.com/api/?name=Alya+Zahiyyah&background=B76E79&color=fff&size=80',
                'subtitle' => 'Akun Google Pelanggan Aktif',
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.fashion@gmail.com',
                'avatar' => 'https://ui-avatars.com/api/?name=Siti+Rahma&background=4285F4&color=fff&size=80',
                'subtitle' => 'Akun Google Fashion Enthusiast',
            ],
        ];
    }

    /**
     * Authenticate or register a customer using Google Sign-In profile.
     *
     * @param array{name: string, email: string} $googleData
     * @return User
     */
    public function loginOrCreateGoogleUser(array $googleData): User
    {
        $email = strtolower(trim($googleData['email']));
        $name = trim($googleData['name']);

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(24)),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);
        }

        // Authenticate as customer session
        Auth::login($user, true);

        return $user;
    }
}
