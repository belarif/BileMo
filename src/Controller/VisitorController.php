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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customers/{customer_id}/visitors", name="api_", requirements={"customer_id"="\d+"})
 */
class VisitorController extends AbstractController
{
    /**
     * @Route("", name="create_visitor", methods={"POST"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement, Customer $customer,
        ValidatorInterface $validator
    ): JsonResponse
    {
        try {
            /**
             * @var UserDTO $userDTO
             */
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);

            if($errors->count()) {
                return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
            }

            return $this->json(
                $userManagement->createUser($userDTO, $customer),
                Response::HTTP_CREATED,
                [],
                ['groups' => ['show_visitor','show_customer']]
            );

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );

        }
    }

    /**
     * @Route("", name="visitors_list", methods={"GET"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function list(UserManagement $userManagement, Customer $customer): JsonResponse
    {
        $visitors = $userManagement->users($customer);

        return $this->json($visitors,Response::HTTP_OK,[],['groups' => ['show_visitor','show_customer']]);
    }

    /**
     * @Route("/{visitor_id}", name="show_user", methods={"GET"}, requirements={"visitor_id"="\d+"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     * @Entity("user", expr="repository.getUser(visitor_id)")
     */
    public function show(Request $request, Customer $customer, UserManagement $userManagement, User $user): JsonResponse
    {
        return $this->json(
            $userManagement->showUser($request->get('visitor_id'),
                $customer),
            Response::HTTP_OK,
            [],
            ['groups' => ['show_visitor','show_customer']]
        );
    }

    /**
     * @Route("/{visitor_id}", name="update_visitor", methods={"PUT"}, requirements={"visitor_id"="\d+"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     * @Entity("user", expr="repository.getUser(visitor_id)")
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement,
        User $user,
        Customer $customer,
        ValidatorInterface $validator
    ): JsonResponse
    {
        try {
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);

            if($errors->count()) {
                return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
            }

            return $this->json(
                $userManagement->updateUser($userDTO,$user,$customer),
                Response::HTTP_CREATED,
                [],
                ['groups' => ['show_visitor','show_customer']]
            );

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );

        }
    }

    /**
     * @Route("/{visitor_id}", name="delete_user", methods={"DELETE"}, requirements={"visitor_id"="\d+"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     * @Entity("user", expr="repository.getUser(visitor_id)")
     */
    public function delete(User $user, UserManagement $userManagement, Customer $customer): JsonResponse
    {
        $userManagement->deleteUser($user);

        return $this->json('Le visiteur a été supprimé avec succès',Response::HTTP_OK);
    }
}
