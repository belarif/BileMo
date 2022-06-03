<?php

namespace App\Service;

use App\Entity\Brand;
use App\Entity\DTO\BrandDTO;
use App\Exception\BrandException;
use App\Repository\BrandRepository;

class BrandManagement
{
    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    /**
     * @param $brandDTO
     * @return Brand
     * @throws BrandException
     */
    public function createBrand($brandDTO): Brand
    {
        if ($this->brandRepository->findBy(['name' => $brandDTO->name])) {
            throw BrandException::brandExists($brandDTO->name);
        }

        $brand = new Brand();
        $brand->setName($brandDTO->name);

        return $this->brandRepository->add($brand);
    }

    /**
     * @throws BrandException
     */
    public function brandsList(): array
    {
        $brands = $this->brandRepository->findAll();

        if (!$brands) {
            throw BrandException::notBrandExists();
        }

        return $brands;
    }

    /**
     * @param $brand
     * @param BrandDTO $brandDTO
     * @return Brand
     * @throws BrandException
     */
    public function updateBrand($brand, BrandDTO $brandDTO): Brand
    {
        if ($this->brandRepository->findBy(['name' => $brandDTO->name])) {
            throw BrandException::brandExists($brandDTO->name);
        }

        return $this->brandRepository->add($brand->setName($brandDTO->name));
    }

    public function deleteBrand($brand)
    {
        $this->brandRepository->remove($brand);
    }
}
