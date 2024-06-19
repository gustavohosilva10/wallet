<?php

namespace App\Domain\Transfers\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Transfers\Business\TransferBusiness;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TransferRequest;

/**
 * @OA\Info(
 *     title="API de Transferências",
 *     version="1.0.0",
 *     description="Esta é a documentação da API de Transferências."
 * )
 * @OA\Tag(
 *     name="Transferências",
 *     description="Endpoints relacionados a transferências"
 * )
 */
class TransferController extends Controller
{
    protected $transferBusiness;

    public function __construct(TransferBusiness $transferBusiness)
    {
        $this->transferBusiness = $transferBusiness;
    }

    /**
     * @OA\Post(
     *     path="/transfer",
     *     operationId="transfer",
     *     tags={"Transferências"},
     *     summary="Realiza uma transferência",
     *     description="Este endpoint realiza uma transferência entre usuários.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransferRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transferência realizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na transferência"
     *     )
     * )
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        try {
            $transfer = $this->transferBusiness->transfer($request->all());
            return response()->json($transfer, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

