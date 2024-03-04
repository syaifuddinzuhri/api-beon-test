<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Traits\GlobalTrait;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    use GlobalTrait;

    public function login($request)
    {
        try {
            $payload = $request->only(['email', 'password']);
            $admin = Admin::where('email', $payload['email'])->first();
            if (!$admin) $this->ApiException('Email tidak ditemukan');

            if (!Hash::check($payload['password'], $admin->password)) $this->ApiException('Password salah');

            if (!$token = auth('api')->login($admin)) $this->ApiException("Login gagal");

            $admin['token'] = $token;
            return $admin;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function me()
    {
        try {
            $admin = Admin::find($this->getUserAuth()->id);
            return $admin;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }

    public function logout()
    {
        try {
            auth('api')->logout();
            return true;
        } catch (\Exception $e) {
            throw $e;
            report($e);
            return $e;
        }
    }
}
