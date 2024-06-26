<?php

namespace App\Domain\Bracket\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Bracket\Business\BracketBusiness;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BracketRequest;

  /**
     * @OA\Info(
     *     title="API de Contatos",
     *     version="1.0.0",
     *     description="Esta é a documentação da API contatos."
     * )
     */
class BracketController extends Controller
{
    protected $bracketBusiness;

    public function __construct(BracketBusiness $bracketBusiness)
    {
        $this->bracketBusiness = $bracketBusiness;
    }

    /**
     * @OA\Post(
     *     path="/validate-brackets",
     *     summary="Validate bracket sequence",
     *     tags={"Brackets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BracketRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Valid sequence",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Valid sequence")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid sequence",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invalid sequence")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The brackets field is required.")
     *         )
     *     )
     * )
     */
    public function validateBrackets(BracketRequest $request): JsonResponse
    {
        try {
            $input = $this->bracketBusiness->isValidBracketSequence($request->input('brackets'));
            if ($input) {
                return response()->json(['message' => 'Valid sequence'], 200);
            } else {
                return response()->json(['message' => 'Invalid sequence'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

