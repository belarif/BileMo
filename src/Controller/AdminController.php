<?php

namespace App\Controller;

use App\Entity\DTO\UserDTO;
use App\Exception\UserException;
use App\Repository\UserRepository;
use App\Service\UserManagement;
use Exception;
use Hateoas\HateoasBuilder;
use OpenApi\Annotations as OA;
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
     *     tags={"Admins management"},
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
     *                 property="roles",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *                 )
     *             ),
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
                        'message' => $errors[0]->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->hateoasResponse($userManagement->createUser($userDTO, null));
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
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
     *     tags={"Admins management"},
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
        try {
            return $this->hateoasResponse($userManagement->users(null));
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @Route("/{admin_id}", name="show_admin", methods={"GET"}, requirements={"admin_id"="\d+"})
     *
     * @OA\Get(
     *     path="/admins/{admin_id}",
     *     summary="Returns admin by id",
     *     tags={"Admins management"},
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
            return $this->hateoasResponse($this->adminUser($userRepository, $admin_id));
        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
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
     *     tags={"Admins management"},
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
     *                 property="roles",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *                 )
     *             ),
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

            return $this->hateoasResponse($userManagement->updateUser($userDTO, $this->adminUser($userRepository, $admin_id), null));
        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
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
     *     tags={"Admins management"},
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
            $userManagement->deleteUser($this->adminUser($userRepository, $admin_id));

            return $this->json('L\'administrateur a été supprimé avec succès', Response::HTTP_NO_CONTENT);
        } catch (UserException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    private function hateoasResponse($data): JsonResponse
    {
        $hateoas = HateoasBuilder::create()->build();

        return new JsonResponse($hateoas->serialize($data, 'json'), Response::HTTP_OK, [], 'json');
    }

    private function adminUser($userRepository, $admin_id)
    {
        return $userRepository->getAdmin($admin_id, 1);
    }
}
