<?php

namespace App\Controller;

use App\Exception\UserException;
use App\Repository\UserRepository;
use OpenApi\Annotations as OA;
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
     *
     * @OA\Post(
     *     path="/admins",
     *     summary="Create a new admin",
     *     tags="G",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email"
     *             ),
     *             @OA\Property(
     *                 property="password"
     *             ),
     *             @OA\Property(
     *                 property="roles"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Created"
     *         )
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="HTTP_CONFLICT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Conflict"
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="HTTP_BAD_REQUEST",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Bad request"
     *         )
     *     )
     * )
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
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($userManagement->createUser($userDTO, null), Response::HTTP_CREATED, [], ['groups' => 'show_admin']);

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
     * @Route("", name="admins_list", methods={"GET"})
     *
     * @OA\Get(
     *     path="/admins",
     *     summary="Returns list of admins",
     *     tags="G",
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(UserManagement $userManagement): JsonResponse
    {
        return $this->json($admins = $userManagement->users(null), Response::HTTP_OK, [], ['groups' => 'show_admin']);
    }

    /**
     * @Route("/{admin_id}", name="show_admin", methods={"GET"}, requirements={"admin_id"="\d+"})
     *
     * @OA\Get(
     *     path="/admins/{admin_id}",
     *     summary="Returns admin by id",
     *     tags="G",
     *     @OA\Parameter(
     *         name="admin_id",
     *         in="path",
     *         description="admin ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserDTO"),
     *             description="Ok"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
     */
    public function show(int $admin_id, UserRepository $userRepository): JsonResponse
    {
        try {
            return $this->json($userRepository->getUser($admin_id), Response::HTTP_OK, [], ['groups' => 'show_admin']);

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
     * @Route("/{admin_id}", name="update_admin", methods={"PUT"}, requirements={"admin_id"="\d+"})
     *
     * @OA\Put(
     *     path="/admins/{admin_id}",
     *     summary="Updates an admin by id",
     *     tags="G",
     *     @OA\Parameter(
     *         name="admin_id",
     *         in="path",
     *         description="admin ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email"
     *             ),
     *             @OA\Property(
     *                 property="password"
     *             ),
     *             @OA\Property(
     *                 property="roles"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserDTO"),
     *             description="Created"
     *         )
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="HTTP_CONFLICT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Conflict"
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="HTTP_BAD_REQUEST",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Bad request"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
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
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json(
                $userManagement->updateUser($userDTO,$userRepository->getUser($admin_id), null),
                Response::HTTP_CREATED,
                [],
                ['groups' => 'show_admin']
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
     * @Route("/{admin_id}", name="delete_admin", methods={"DELETE"}, requirements={"admin_id"="\d+"})
     *
     * @OA\Delete(
     *     path="/admins/{admin_id}",
     *     summary="Deletes an admin by id",
     *     tags="G",
     *     @OA\Parameter(
     *         name="admin_id",
     *         in="path",
     *         description="admin ID",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="HTTP_NO_CONTENT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="No content"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
     */
    public function delete(int $admin_id, UserRepository $userRepository, UserManagement $userManagement): JsonResponse
    {
        try {
            $userManagement->deleteUser($userRepository->getUser($admin_id));

            return $this->json('L\'administrateur a été supprimé avec succès', Response::HTTP_NO_CONTENT);

        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' =>$e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );

        }
    }
}
