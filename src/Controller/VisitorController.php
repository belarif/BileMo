<?php

namespace App\Controller;

use App\Exception\UserException;
use Exception;
use App\Entity\DTO\UserDTO;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use App\Service\UserManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customers/{customer_id}/visitors", name="api_", requirements={"customer_id"="\d+"})
 */
class VisitorController extends AbstractController
{
    /**
     * @Route("", name="create_visitor", methods={"POST"})
     */
    public function create(
        int $customer_id,
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement,
        CustomerRepository $customerRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            /**
             * @var UserDTO $userDTO
             */
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json(
                $userManagement->createUser($userDTO, $customerRepository->getCustomer($customer_id)),
                Response::HTTP_CREATED,
                [],
                ['groups' => ['show_visitor']]
            );

        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT

            );
        }
    }

    /**
     * @Route("", name="visitors_list", methods={"GET"})
     */
    public function list(int $customer_id, UserManagement $userManagement, CustomerRepository $customerRepository): JsonResponse
    {
        try {
            $visitors = $userManagement->users($customerRepository->getCustomer($customer_id));

            return $this->json($visitors, Response::HTTP_OK, [], ['groups' => ['show_visitor']]);

        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * @Route("/{visitor_id}", name="show_user", methods={"GET"}, requirements={"visitor_id"="\d+"})
     */
    public function show(int $customer_id, int $visitor_id, CustomerRepository $customerRepository, UserRepository $userRepository): JsonResponse
    {
        try {
            $customer = $customerRepository->getCustomer($customer_id);
            $visitor = $userRepository->getVisitorOfCustomer($visitor_id, $customer);

            return $this->json($visitor, Response::HTTP_OK, [], ['groups' => ['show_visitor']]);

        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @Route("/{visitor_id}", name="update_visitor", methods={"PUT"}, requirements={"visitor_id"="\d+"})
     */
    public function update(
        int $visitor_id,
        int $customer_id,
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement,
        UserRepository $userRepository,
        CustomerRepository $customerRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json(
                $userManagement->updateUser($userDTO, $userRepository->getUser($visitor_id), $customerRepository->getCustomer($customer_id)),
                Response::HTTP_CREATED,
                [],
                ['groups' => ['show_visitor']]
            );

        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * @Route("/{visitor_id}", name="delete_user", methods={"DELETE"}, requirements={"visitor_id"="\d+"})
     */
    public function delete(
        int $visitor_id,
        int $customer_id,
        UserRepository $userRepository,
        CustomerRepository  $customerRepository,
        UserManagement $userManagement
    ): JsonResponse
    {
        try {
            $userManagement->deleteUser($userRepository->getVisitorOfCustomer($visitor_id, $customerRepository->getCustomer($customer_id)));

            return $this->json('Le visiteur a été supprimé avec succès', Response::HTTP_OK);

        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );

        }
    }
}
