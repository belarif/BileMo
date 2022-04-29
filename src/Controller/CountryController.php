<?php

namespace App\Controller;

use App\Entity\DTO\CountryDTO;
use App\Service\CountryManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/countries", name="api_")
 */
class CountryController extends AbstractController
{
    /**
     * @Route("", name="create_country", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param CountryManagement $countryManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, SerializerInterface $serializer, CountryManagement $countryManagement): JsonResponse
    {
        $countryDTO = $serializer->deserialize($request->getContent(),CountryDTO::class,'json');

        $countryManagement->createCountry($countryDTO);

        return $this->json('La pays a été ajouté avec succès',200,['Content-Type' => 'text/plain']);
    }
}
