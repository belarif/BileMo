<?php

namespace App\Controller;

use App\Entity\DTO\UserDTO;
use App\Service\UserManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admins", name="api_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="create_admin", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, UserManagement $userManagement): JsonResponse
    {
        /**
         * @var UserDTO $userDTO
         */
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

        $userManagement->createUser($userDTO, $customer = null);

        return $this->json('L\'administrateur a été créé avec succès',Response::HTTP_CREATED);
    }
}
