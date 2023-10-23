<?php

namespace App\Services\Auth;

use App\Exceptions\NotFoundException;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthService
{
    public function login(array $request)
    {
        $user = ModelsUser::whereEmail($request['email'])->first();
        if(empty($user)) {
            throw new NotFoundException('Email ou Senha inválida', Response::HTTP_FORBIDDEN);

        }

        if (!Hash::check($request['password'], $user->password)) {
            throw new NotFoundException('Email ou Senha inválida', Response::HTTP_FORBIDDEN);
        }

        $token = $user->createToken('user Token')->plainTextToken;

        $user = [
            'token' => $token,
            'user' => $user
        ];

        return $user;
    }
}
