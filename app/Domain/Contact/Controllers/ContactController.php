<?php

namespace App\Domain\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Contact\Business\ContactBusiness;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Contact;

/**
 * @OA\Tag(
 *     name="Contatos",
 *     description="Endpoints relacionados a contatos"
 * )
 */
class ContactController extends Controller
{
    protected $contactBusiness;

    public function __construct(ContactBusiness $contactBusiness)
    {
        $this->contactBusiness = $contactBusiness;
    }

    /**
     * @OA\Get(
     *     path="/api/contacts",
     *     summary="Lista todos os contatos",
     *     tags={"Contatos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contatos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Contact")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao listar contatos")
     *         )
     *     )
     * )
     */
    public function getContacts(): JsonResponse
    {
        try {
            return response()->json(['data' => $this->contactBusiness->getContacts()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/contacts",
     *     summary="Cria um novo contato",
     *     tags={"Contatos"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contato criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Contact")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao criar contato")
     *         )
     *     )
     * )
     */
    public function storeContact(StoreContactRequest $request): JsonResponse
    {
        try {
            $contact = $this->contactBusiness->storeContact($request->validated());
            return response()->json(['data' => $contact], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/contacts/{contact}",
     *     summary="Obtém detalhes de um contato",
     *     tags={"Contatos"},
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         required=true,
     *         description="ID do contato",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do contato",
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao obter contato")
     *         )
     *     )
     * )
     */
    public function showContact($idContact): JsonResponse
    {
        try {
            return response()->json(['data' => $this->contactBusiness->showContact($idContact)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/contacts/{contact}",
     *     summary="Atualiza um contato",
     *     tags={"Contatos"},
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         required=true,
     *         description="ID do contato",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contato atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Contact")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao atualizar contato")
     *         )
     *     )
     * )
     */
    public function updateContact(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        try {
            $updatedContact = $this->contactBusiness->updateContact($contact->id, $request->all());
            return response()->json(['data' => $updatedContact], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/contacts/{contact}",
     *     summary="Exclui um contato",
     *     tags={"Contatos"},
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         required=true,
     *         description="ID do contato",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Contato excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao excluir contato")
     *         )
     *     )
     * )
     */
    public function destroyContact(Contact $contact): JsonResponse
    {
        try {
            $this->contactBusiness->destroyContact($contact);
            return response()->json(['message'=> 'contact removed success.'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
