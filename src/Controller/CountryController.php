<?php

namespace App\Controller;

use App\Entity\Country;
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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, SerializerInterface $serializer, CountryManagement $countryManagement): JsonResponse
    {
        $countryDTO = $serializer->deserialize($request->getContent(),CountryDTO::class,'json');

        $countryManagement->createCountry($countryDTO);

        return $this->json('La pays a été ajouté avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("", name="countries_list", methods={"GET"})
     */
    public function list(CountryManagement $countryManagement): JsonResponse
    {
        return $this->json($countryManagement->countriesList(),'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_country", methods={"GET"})
     */
    public function show(Country $country): JsonResponse
    {
        return $this->json($country,'200',['Content-Type' => 'application/json']);
    }
}
