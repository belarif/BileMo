<?php

namespace App\Service;

use App\Entity\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\CountryRepository;
use App\Repository\MemoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;

class ProductManagement
{
    protected EntityManagerInterface $em;

    private ProductRepository $productRepository;

    private BrandRepository $brandRepository;

    private MemoryRepository $memoryRepository;

    private CountryRepository $countryRepository;

    private UserRepository $userRepository;

    private ColorRepository $colorRepository;

    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $productRepository,
        BrandRepository $brandRepository,
        MemoryRepository $memoryRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ColorRepository $colorRepository
    )
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->memoryRepository = $memoryRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->colorRepository = $colorRepository;
    }

    /**
     * @param ProductDTO $productDTO
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createProduct(ProductDTO $productDTO)
    {
        $brand = $this->brandRepository->findOneBy(['id' => $productDTO->brand->id]);
        $memory = $this->memoryRepository->findOneBy(['id' => $productDTO->memory->id]);
        $country = $this->countryRepository->findOneBy(['id' => $productDTO->country->id]);
        $user = $this->userRepository->findOneBy(['id' => $productDTO->user->id]);

        $product = new Product();
        $product->setName($productDTO->name);
        $product->setDescription($productDTO->description);
        $product->setBrand($brand);
        $product->setMemory($memory);
        $product->setCountry($country);
        $product->setUser($user);

        foreach ($productDTO->getColors() as $color) {
            $product->addColor($this->colorRepository->findOneBy(['id' => $color->id]));
        }

        $this->productRepository->add($product);
    }

    public function showProduct(int $product_id)
    {
        return $this->productRepository->findOneBy(['id' => $product_id]);
    }
}