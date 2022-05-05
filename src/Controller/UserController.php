<?php

namespace App\Controller;

use App\Entity\Customer;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customers/{customer_id}/users", name="api_", requirements={"customer_id"="\d+"})
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="create_user", methods={"POST"})
     *
     * @Entity("customer", expr="repository.find(customer_id)")
     */
    public function create(Request $request, SerializerInterface $serializer, UserManagement $userManagement, Customer $customer, ValidatorInterface $validator): JsonResponse
    {
        /**
         * @var UserDTO $userDTO
         */
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

        $errors = $validator->validate($userDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $userManagement->createUser($userDTO, $customer);

        return $this->json('L\'utilisateur a été créé avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="users_list", methods={"GET"})
     *
     * @Entity("customer", expr="repository.find(customer_id)")
     */
    public function list(UserManagement $userManagement, Customer $customer): JsonResponse
    {
        return $this->json($userManagement->usersOfCustomer($customer),'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{user_id}", name="show_user", methods={"GET"}, requirements={"user_id"="\d+"})
     *
     * @Entity("customer", expr="repository.find(customer_id)")
     */
    public function show(Request $request, Customer $customer, UserManagement $userManagement): JsonResponse
    {
        $user = $userManagement->showUser($request->get('user_id'),$customer);

        return $this->json($user,'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{user_id}", name="update_user", methods={"PUT"}, requirements={"user_id"="\d+"})
     *
     * @Entity("customer", expr="repository.find(customer_id)")
     * @Entity("user", expr="repository.find(user_id)")
     */
    public function update(Request $request, SerializerInterface $serializer, UserManagement $userManagement, User $user, Customer $customer, ValidatorInterface $validator): JsonResponse
    {
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

        $errors = $validator->validate($userDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $userManagement->updateUser($userDTO,$user,$customer);

        return new JsonResponse('L\'utilisateur est mise à jour avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("/{user_id}", name="delete_user", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("customer", expr="repository.find(customer_id)")
     * @Entity("user", expr="repository.getUser(user_id)")
     */
    public function delete(User $user, UserManagement $userManagement): JsonResponse
    {
        $userManagement->deleteUser($user);

        return $this->json('L\'utilisateur a été supprimé avec succès');
    }
}

