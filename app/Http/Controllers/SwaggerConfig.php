<?php

namespace App\Http\Controllers;

class SwaggerConfig
{
  

    /**
     * @OA\Schema(
     *     schema="Contact",
     *     type="object",
     *     required={"id", "type", "contact"},
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="ID do contato"
     *     ),
     *     @OA\Property(
     *         property="type",
     *         type="string",
     *         description="Tipo de contato"
     *     ),
     *     @OA\Property(
     *         property="contact",
     *         type="string",
     *         description="Informação do contato"
     *     )
     * )
     */
    public function __construct()
    {
    }
}
