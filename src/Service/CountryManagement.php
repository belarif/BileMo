<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\DTO\CountryDTO;
use App\Exception\CountryException;
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
     * @throws CountryException
     */
    public function createCountry(CountryDTO $countryDTO): Country
    {
        if($this->countryRepository->findBy(['name' => $countryDTO->name])) {
            throw CountryException::countryExists($countryDTO->name);
        }

        $country = new Country();
        $country->setName($countryDTO->name);

        return $this->countryRepository->add($country);
    }

    public function countriesList(): array
    {
        return $this->countryRepository->findAll();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws CountryException
     */
    public function updateCountry($country, CountryDTO $countryDTO): Country
    {
        if($this->countryRepository->findBy(['name' => $countryDTO->name])) {
            throw CountryException::countryExists($countryDTO->name);
        }

        return $this->countryRepository->add($country->setName($countryDTO->name));
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteCountry($country)
    {
        $this->countryRepository->remove($country);
    }
}
