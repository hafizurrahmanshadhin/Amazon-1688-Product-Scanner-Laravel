<?php

namespace App\Services\Api\Auth;

use App\Models\User;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterService {
    /**
     * Handle the registration process.
     */
    public function register(array $data): array {
        $existingUser = User::where('email', $data['email'])->exists();
        if ($existingUser) {
            throw new Exception('The email has already been taken.');
        }

        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => bcrypt($data['password']),
            ]);
        } catch (Exception $e) {
            throw new Exception('User registration failed: ' . $e->getMessage());
        }

        try {
            $token = JWTAuth::attempt(['email' => $data['email'], 'password' => $data['password']]);
            if (!$token) {
                throw new Exception('Authentication failed.');
            }
        } catch (JWTException $e) {
            throw new Exception('Could not create token: ' . $e->getMessage());
        }

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
