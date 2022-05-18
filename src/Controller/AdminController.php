<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Exception;
use App\Entity\DTO\UserDTO;
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
                return $this->json(
                    [
                        'status' => Response::HTTP_CONFLICT,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_CONFLICT);
            }

            return $this->json($userManagement->createUser($userDTO, null), Response::HTTP_CREATED, [], ['groups' => 'show_admin']);

        } catch (Exception $e) {
            return $this->json(
                [
                'status' => Response::HTTP_CONFLICT,
                'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );

        }
    }

    /**
     * @Route("", name="admins_list", methods={"GET"})
     */
    public function list(UserManagement $userManagement): JsonResponse
    {
        return $this->json($admins = $userManagement->users(null), Response::HTTP_OK, [], ['groups' => 'show_admin']);
    }

    /**
     * @Route("/{admin_id}", name="show_admin", methods={"GET"}, requirements={"admin_id"="\d+"})
     */
    public function show(int $admin_id, UserRepository $userRepository): JsonResponse
    {
        try {
            return $this->json($userRepository->getUser($admin_id), Response::HTTP_OK, [], ['groups' => 'show_admin']);

        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => Response::HTTP_CONFLICT,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );

        }
    }

    /**
     * @Route("/{admin_id}", name="update_admin", methods={"PUT"}, requirements={"admin_id"="\d+"})
     */
    public function update(
        int $admin_id,
        Request $request,
        SerializerInterface $serializer,
        UserManagement $userManagement,
        UserRepository $userRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $userDTO = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');

            $errors = $validator->validate($userDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'status' => Response::HTTP_CONFLICT,
                        'message' => $errors[0]->getMessage()
                    ]
                    ,Response::HTTP_CONFLICT);
            }

            return $this->json(
                $userManagement->updateUser($userDTO,$userRepository->getUser($admin_id), null),
                Response::HTTP_CREATED,
                [],
                ['groups' => 'show_admin']
            );

        } catch (Exception $e) {
            return $this->json([
                'status' => Response::HTTP_CONFLICT,
                'message' => $e->getMessage(), ],
                Response::HTTP_CONFLICT
            );

        }
    }

    /**
     * @Route("/{admin_id}", name="delete_admin", methods={"DELETE"}, requirements={"admin_id"="\d+"})
     */
    public function delete(int $admin_id, UserRepository $userRepository, UserManagement $userManagement): JsonResponse
    {
        try {
            $userManagement->deleteUser($userRepository->getUser($admin_id));

            return $this->json('L\'administrateur a été supprimé avec succès', Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => Response::HTTP_CONFLICT,
                    'message' =>$e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );

        }
    }
}
