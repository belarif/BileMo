<?php

namespace App\Controller;

use App\Entity\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


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

    /**
     * @Route("", name="admins_list", methods={"GET"})
     */
    public function list(UserManagement $userManagement): JsonResponse
    {
        return $this->json($userManagement->users($customer = null),Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_admin", methods={"GET"}, requirements={"user_id"="\d+"})
     */
    public function show(Request $request, UserManagement $userManagement): JsonResponse
    {
        $user = $userManagement->showUser($request->get('id'), $customer = null);

        return $this->json($user,Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_admin", methods={"PUT"}, requirements={"user_id"="\d+"})
     *
     * @Entity("user", expr="repository.getUser(id)")
     */
    public function update(Request $request, SerializerInterface $serializer, UserManagement $userManagement, User $user): JsonResponse
    {
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

        $userManagement->updateUser($userDTO,$user,$customer=null);

        return new JsonResponse('L\'administrateur est mise à jour avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete_admin", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("user", expr="repository.getUser(id)")
     */
    public function delete(User $user, UserManagement $userManagement): JsonResponse
    {
        $userManagement->deleteUser($user);

        return $this->json('L\'administrateur a été supprimé avec succès',Response::HTTP_OK);
    }
}
