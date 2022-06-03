<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/login",
 *     summary="JWT login",
 *     tags={"Security management"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 type="string",
 *                 property="username"
 *             ),
 *             @OA\Property(
 *                 type="string",
 *                 property="password"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="HTTP_OK",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     type="string",
 *                     property="JWT token"
 *                 )
 *         )
 *     )
 * )
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="api_login")
     */
    public function login(): JsonResponse
    {
        return $this->json(
            [
                'message' => 'Vous vous êtes authentifié avec succès',
            ], Response::HTTP_OK
        );
    }
}
