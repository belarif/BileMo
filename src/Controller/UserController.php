<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="app_user")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Vous vous etes authentifié avec succès'
        ]);
    }
}
