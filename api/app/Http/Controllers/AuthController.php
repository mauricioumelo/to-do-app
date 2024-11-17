<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->service->register($data);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $token = $this->service->login($credentials);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        $this->service->logout();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
}
