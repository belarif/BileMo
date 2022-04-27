<?php

namespace App\Service;

use App\Entity\Brand;
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
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createBrand($brandDTO)
    {
        $brand = new Brand();
        $brand->setName($brandDTO->name);

        $this->brandRepository->add($brand);
    }

    public function brandsList(): array
    {
        return $this->brandRepository->findAll();
    }
}