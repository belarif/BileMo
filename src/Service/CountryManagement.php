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
     * @param CountryDTO $countryDTO
     * @return Country
     * @throws CountryException
     */
    public function createCountry(CountryDTO $countryDTO): Country
    {
        if ($this->countryRepository->findBy(['name' => $countryDTO->name])) {
            throw CountryException::countryExists($countryDTO->name);
        }

        $country = new Country();
        $country->setName($countryDTO->name);

        return $this->countryRepository->add($country);
    }

    /**
     * @throws CountryException
     */
    public function countriesList(): array
    {
        $countries = $this->countryRepository->findAll();

        if (!$countries) {
            throw CountryException::notCountryExists();
        }

        return $countries;
    }

    /**
     * @param $country
     * @param CountryDTO $countryDTO
     * @return Country
     * @throws CountryException
     */
    public function updateCountry($country, CountryDTO $countryDTO): Country
    {
        if ($this->countryRepository->findBy(['name' => $countryDTO->name])) {
            throw CountryException::countryExists($countryDTO->name);
        }

        return $this->countryRepository->add($country->setName($countryDTO->name));
    }

    public function deleteCountry($country)
    {
        $this->countryRepository->remove($country);
    }
}
