<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Services\Auth\AuthService;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AuthController extends Controller //implements HasMiddleware
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $registerRequest){
        return $this->authService->register( $registerRequest->validated());
    }
    public function login(LoginRequest $loginReq)
    {
        return $this->authService->login($loginReq->validated());
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
