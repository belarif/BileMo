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

    /**
     * @Route("", name="countries_list", methods={"GET"})
     * @param CountryManagement $countryManagement
     * @return JsonResponse
     */
    public function list(CountryManagement $countryManagement): JsonResponse
    {
        return $this->json($countryManagement->countriesList(),'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_country", methods={"GET"})
     * @param Country $country
     * @return JsonResponse
     */
    public function show(Country $country): JsonResponse
    {
        return $this->json($country,'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_country", methods={"PUT"})
     * @param Request $request
     * @param Country $country
     * @param CountryManagement $countryManagement
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Request $request, Country $country, CountryManagement $countryManagement, SerializerInterface $serializer): JsonResponse
    {
        $countryDTO = $serializer->deserialize($request->getContent(),CountryDTO::class,'json');
        $countryManagement->updateCountry($country,$countryDTO);

        return $this->json('La pays a été modifié avec succès');
    }

    /**
     * @Route("/{id}", name="delete_country", methods={"DELETE"})
     * @param Country $country
     * @param CountryManagement $countryManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Country $country, CountryManagement $countryManagement): JsonResponse
    {
        $countryManagement->deleteCountry($country);

        return $this->json('La pays a été supprimé avec succès',200,['Content-Type' => 'text/plain']);
    }
}
