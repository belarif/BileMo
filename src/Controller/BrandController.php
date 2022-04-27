<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\DTO\BrandDTO;
use App\Service\BrandManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brands", "api_")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("", name="create_brand", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param BrandManagement $brandManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, SerializerInterface $serializer, BrandManagement $brandManagement): JsonResponse
    {
        $brandDTO = $serializer->deserialize($request->getContent(),BrandDTO::class,'json');

        $brandManagement->createBrand($brandDTO);

        return $this->json('La marque a été ajouté avec succès');
    }

    /**
     * @Route("", name="brands_list", methods={"GET"})
     * @param BrandManagement $brandManagement
     * @return JsonResponse
     */
    public function list(BrandManagement $brandManagement): JsonResponse
    {
        return $this->json($brandManagement->brandsList(),'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_brand", methods={"GET"})
     * @param Brand $brand
     * @return JsonResponse
     */
    public function show(Brand $brand): JsonResponse
    {
        return $this->json($brand,'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_brand", methods={"PUT"})
     * @param Request $request
     * @param Brand $brand
     * @param BrandManagement $brandManagement
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Request $request, Brand $brand, BrandManagement $brandManagement, SerializerInterface $serializer): JsonResponse
    {
        $brandDTO = $serializer->deserialize($request->getContent(),BrandDTO::class,'json');
        $brandManagement->updateBrand($brand,$brandDTO);

        return $this->json('La marque a été modifié avec succès');
    }

}