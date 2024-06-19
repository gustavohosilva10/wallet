<?php

namespace App\Domain\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Users\Business\UserBusiness;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    protected $userBusiness;

    public function __construct(UserBusiness $userBusiness)
    {
        $this->userBusiness = $userBusiness;
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     operationId="registerUser",
     *     tags={"Usuários"},
     *     summary="Registra um novo usuário",
     *     description="Este endpoint registra um novo usuário.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro no registro"
     *     )
     * )
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        try {
            $register = $this->userBusiness->register($request->all());
            return response()->json($register, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

