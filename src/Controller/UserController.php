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
 * @Route("/customers/{customer_id}/users", name="api_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="create_user", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserManagement $userManagement
     * @param Customer $customer
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, SerializerInterface $serializer, UserManagement $userManagement, Customer $customer): JsonResponse
    {
        /**
         * @var UserDTO $userDTO
         */
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $userManagement->createUser($userDTO, $customer);

        return $this->json('L\'utilisateur a été créé avec succès');
    }

    /**
     * @Route("", name="users_list", methods={"GET"})
     * @param UserManagement $userManagement
     * @param Customer $customer
     * @return JsonResponse
     */
    public function list(UserManagement $userManagement, Customer $customer): JsonResponse
    {
        return $this->json($userManagement->usersList($customer),'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{user_id}", name="show_user", methods={"GET"})
     * @Entity("customer", expr="repository.find(customer_id)")
     * @param Request $request
     * @param Customer $customer
     * @param UserManagement $userManagement
     * @return JsonResponse
     */
    public function show(Request $request, Customer $customer, UserManagement $userManagement): JsonResponse
    {
        $user = $userManagement->showUser($request->get('user_id'),$customer);
        return $this->json($user,'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{user_id}", name="update_user", methods={"PUT"})
     * @Entity("customer", expr="repository.find(customer_id)")
     * @Entity("user", expr="repository.find(user_id)")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserManagement $userManagement
     * @param User $user
     * @param Customer $customer
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Request $request, SerializerInterface $serializer, UserManagement $userManagement, User $user, Customer $customer): JsonResponse
    {
        $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $userManagement->updateUser($userDTO,$user,$customer);

        return new JsonResponse('L\'utilisateur est mise à jour avec succès');
    }

    /**
     * @Route("/{user_id}", name="delete_user", methods={"DELETE"}, requirements={"id"="\d+"})
     * @Entity("customer", expr="repository.find(customer_id)")
     * @Entity("user", expr="repository.find(user_id)")
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(User $user, UserManagement $userManagement)
    {
        $userManagement->deleteUser($user);

        return $this->json('L\'utilisateur a été supprimé avec succès');
    }
}
