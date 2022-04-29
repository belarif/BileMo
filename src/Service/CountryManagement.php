<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\DTO\CountryDTO;
use App\Repository\CountryRepository;

class CountryManagement
{
    private CountryRepository $countryRepository;

    /**
     * @param CountryRepository $countryRepository
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param CountryDTO $countryDTO
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCountry(CountryDTO $countryDTO)
    {
        $country = new Country();
        $country->setName($countryDTO->name);

        $this->countryRepository->add($country);
    }
}