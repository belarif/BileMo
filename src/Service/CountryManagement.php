<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\DTO\CountryDTO;
use App\Repository\CountryRepository;

class CountryManagement
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCountry(CountryDTO $countryDTO): void
    {
        $country = new Country();
        $country->setName($countryDTO->name);

        $this->countryRepository->add($country);
    }

    public function countriesList(): array
    {
        return $this->countryRepository->findAll();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateCountry($country, CountryDTO $countryDTO): Void
    {
        $country->setName($countryDTO->name);

        $this->countryRepository->add($country);
    }

    /**
     * @param $country
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteCountry($country)
    {
        $this->countryRepository->remove($country);
    }
}