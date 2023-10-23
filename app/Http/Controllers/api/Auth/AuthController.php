<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Validators\UserRequest;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    protected $authService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(UserRequest $validator)
    {
        try {
            $request = $validator->validateLogin();
            $result = $this->authService->login($request);
            return ApiResponse::success($result, 200);

        }catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function logout()
    {
        try {

            if (Auth::check()) {
              // Obtém todos os tokens de acesso pessoais do usuário
                $tokens = PersonalAccessToken::where('tokenable_id', auth()->user()->id)->get();

                // Revoga cada um dos tokens
                foreach ($tokens as $token) {
                    $token->delete();
                }
            }
            return ApiResponse::success([], 200, 'Usuário deslogado com sucesso');
        }catch (\Exception $e) {
            return ApiResponse::Error($e->getMessage());
        }
    }
}
