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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/countries", name="api_")
 */
class CountryController extends AbstractController
{
    /**
     * @Route("", name="create_country", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, CountryManagement $countryManagement): JsonResponse
    {
        $countryDTO = $serializer->deserialize($request->getContent(),CountryDTO::class,'json');

        $countryManagement->createCountry($countryDTO);

        return $this->json('Le pays a été ajouté avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("", name="countries_list", methods={"GET"})
     */
    public function list(CountryManagement $countryManagement): JsonResponse
    {
        return $this->json($countryManagement->countriesList(),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_country", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Country $country): JsonResponse
    {
        return $this->json($country,200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_country", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function update(Request $request, Country $country, CountryManagement $countryManagement, SerializerInterface $serializer): JsonResponse
    {
        $countryDTO = $serializer->deserialize($request->getContent(),CountryDTO::class,'json');

        $countryManagement->updateCountry($country,$countryDTO);

        return $this->json('Le pays a été modifié avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("/{id}", name="delete_country", methods={"DELETE"}, requirements={"id"="\d+"})
     * @Entity("country", expr="repository.getCountry(id)")
     */
    public function delete(Country $country, CountryManagement $countryManagement): JsonResponse
    {
        $countryManagement->deleteCountry($country);

        return $this->json('La pays a été supprimé avec succès',200,['Content-Type' => 'text/plain']);
    }
}

