<?php

namespace App\Controller;

use App\Entity\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admins", name="api_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="create_admin", methods={"POST"})
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            /**
             * @var UserDTO $userDTO
             */
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);

            if ($errors->count()) {
                return $this->json($errors[0]->getMessage(), Response::HTTP_CONFLICT);
            }

            return $this->json($userManagement->createUser($userDTO, $customer = null), Response::HTTP_CREATED, [], ['groups' => 'show_admin']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("", name="admins_list", methods={"GET"})
     */
    public function list(UserManagement $userManagement): JsonResponse
    {
        $admins = $userManagement->users($customer = null);

        return $this->json($admins, Response::HTTP_OK, [], ['groups' => 'show_admin']);
    }

    /**
     * @Route("/{id}", name="show_admin", methods={"GET"}, requirements={"user_id"="\d+"})
     */
    public function show(Request $request, UserManagement $userManagement): JsonResponse
    {
        $admin = $userManagement->showUser($request->get('id'), $customer = null);

        return $this->json($admin, Response::HTTP_OK, [], ['groups' => 'show_admin']);
    }

    /**
     * @Route("/{id}", name="update_admin", methods={"PUT"}, requirements={"user_id"="\d+"})
     *
     * @Entity("user", expr="repository.getUser(id)")
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement,
        User $user,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);

            if ($errors->count()) {
                return $this->json($errors[0]->getMessage(), Response::HTTP_CONFLICT);
            }

            return $this->json(
                $userManagement->updateUser($userDTO, $user, $customer = null),
                Response::HTTP_CREATED,
                [],
                ['groups' => 'show_admin']
            );
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/{id}", name="delete_admin", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("user", expr="repository.getUser(id)")
     */
    public function delete(User $user, UserManagement $userManagement): JsonResponse
    {
        $userManagement->deleteUser($user);

        return $this->json('L\'administrateur a été supprimé avec succès', Response::HTTP_OK);
    }
}
