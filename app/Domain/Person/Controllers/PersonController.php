<?php

namespace App\Domain\Person\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Person\Business\PersonBusiness;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Person;
use App\Jobs\ProcessContacts;

/**
 * @OA\Tag(
 *     name="Pessoas",
 *     description="Endpoints relacionados a pessoas"
 * )
 */
class PersonController extends Controller
{
    protected $personBusiness;

    public function __construct(PersonBusiness $personBusiness)
    {
        $this->personBusiness = $personBusiness;
    }

    /**
     * @OA\Get(
     *     path="/api/people",
     *     summary="Lista todas as pessoas com seus contatos",
     *     tags={"Pessoas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pessoas com contatos",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao listar pessoas")
     *         )
     *     )
     * )
     */
    public function getPersons(): JsonResponse
    {
        try {
            return response()->json(['data' => $this->personBusiness->getPersonsWithContact()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/people",
     *     summary="Cria uma nova pessoa com contatos",
     *     tags={"Pessoas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePersonRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pessoa criada com sucesso",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao criar pessoa")
     *         )
     *     )
     * )
     */
    public function storePerson(StorePersonRequest $request): JsonResponse
    {
        try {
            $person = $this->personBusiness->storePerson($request->validated());
            if (isset($request->contacts)) {
                ProcessContacts::dispatch($person, $request->contacts);
            }
            return response()->json(['data' => $person], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/people/{person}",
     *     summary="Obtém detalhes de uma pessoa com contatos",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="person",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da pessoa com contatos",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *     )
     * )
     */
    public function showPerson(Person $person): JsonResponse
    {
        try {
            return response()->json(['data' => $this->personBusiness->getPersonWithContacts($person)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/people/{person}",
     *     summary="Atualiza uma pessoa e seus contatos",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="person",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePersonRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa atualizada com sucesso",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao atualizar pessoa")
     *         )
     *     )
     * )
     */
    public function updatePerson(UpdatePersonRequest $request, Person $person): JsonResponse
    {
        try {
            $updatedPerson = $this->personBusiness->updatePerson($person, $request->validated());
            if (isset($request->contacts)) {
                ProcessContacts::dispatch($updatedPerson, $request->contacts);
            }
            return response()->json(['data' => $updatedPerson], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/people/{person}",
     *     summary="Exclui uma pessoa e seus contatos",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="person",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Pessoa excluída com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao excluir pessoa")
     *         )
     *     )
     * )
     */
    public function destroyPerson(Person $person): JsonResponse
    {
        try {
            $this->personBusiness->destroyPerson($person);
            return response()->json(['message'=> 'person removed success.'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
