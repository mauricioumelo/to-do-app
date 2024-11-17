<?php

namespace App\Services;

use App\Exceptions\AuthFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function login(array $credentials): string
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthFailedException();
        }

        /** @var User $user */
        $user = Auth::user();
        return $user->createToken('API Token')->plainTextToken;
    }

    public function logout(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
    }
}
