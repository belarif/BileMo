<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/customers/{customer_id}/users", name="api_", requirements={"customer_id"="\d+"})
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="create_user", methods={"POST"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function create(Request $request, SerializerInterface $serializer, UserManagement $userManagement, Customer $customer): JsonResponse
    {
        /**
         * @var UserDTO $userDTO
         */
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $userManagement->createUser($userDTO, $customer);

        return $this->json('L\'utilisateur a été créé avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("", name="users_list", methods={"GET"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function list(UserManagement $userManagement, Customer $customer): JsonResponse
    {
        return $this->json($userManagement->usersOfCustomer($customer),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{user_id}", name="show_user", methods={"GET"}, requirements={"user_id"="\d+"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     * @Entity("user", expr="repository.getUser(user_id)")
     */
    public function show(Request $request, Customer $customer, UserManagement $userManagement, User $user): JsonResponse
    {
        return $this->json($userManagement->showUser($request->get('user_id'),$customer),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{user_id}", name="update_user", methods={"PUT"}, requirements={"user_id"="\d+"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     * @Entity("user", expr="repository.getUser(user_id)")
     */
    public function update(Request $request, SerializerInterface $serializer, UserManagement $userManagement, User $user, Customer $customer): JsonResponse
    {
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $userManagement->updateUser($userDTO,$user,$customer);

        return $this->json('L\'utilisateur est mise à jour avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("/{user_id}", name="delete_user", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     * @Entity("user", expr="repository.getUser(user_id)")
     */
    public function delete(User $user, UserManagement $userManagement): JsonResponse
    {
        $userManagement->deleteUser($user);

        return $this->json('L\'utilisateur a été supprimé avec succès',200,['Content-Type' => 'text/plain']);
    }
}
