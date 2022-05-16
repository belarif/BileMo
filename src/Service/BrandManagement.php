<?php

namespace App\Service;

use App\Entity\Brand;
use App\Entity\DTO\BrandDTO;
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
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createBrand($brandDTO): Brand
    {
        $brand = new Brand();
        $brand->setName($brandDTO->name);

        return $this->brandRepository->add($brand);
    }

    public function brandsList(): array
    {
        return $this->brandRepository->findAll();
    }

    /**
     * @param $brand
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateBrand($brand, BrandDTO $brandDTO): Brand
    {
        $brand->setName($brandDTO->name);

        return $this->brandRepository->add($brand);
    }

    /**
     * @param $brand
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteBrand($brand)
    {
        $this->brandRepository->remove($brand);
    }
}
