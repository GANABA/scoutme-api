<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Inscription d'un nouvel utilisateur
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'access_token' => $result['token'],
            'token_type'   => 'Bearer',
            'user'         => new \App\Http\Resources\UserResource($result['user']),
        ], 201);
    }

    /**
     * Connexion d'un utilisateur existant
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login(
                $request->validated('email'),
                $request->validated('password')
            );

            return response()->json([
                'access_token' => $result['token'],
                'token_type'   => 'Bearer',
                'user'         => new \App\Http\Resources\UserResource($result['user']),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Identifiants incorrects.',
                'errors'  => $e->errors(),
            ], 401);
        }
    }

    /**
     * Déconnexion de l'utilisateur actuel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }
}
