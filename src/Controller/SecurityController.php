<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="api_login")
     */
    public function login(): JsonResponse
    {
        return $this->json(
            [
                'message' => 'Vous vous êtes authentifié avec succès'
            ], Response::HTTP_OK
        );
    }
}
