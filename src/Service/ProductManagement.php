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
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class ProductManagement
{
    private ProductRepository $productRepository;

    private BrandRepository $brandRepository;

    private MemoryRepository $memoryRepository;

    private CountryRepository $countryRepository;

    private UserRepository $userRepository;

    private ColorRepository $colorRepository;

    public function __construct(
        ProductRepository $productRepository,
        BrandRepository $brandRepository,
        MemoryRepository $memoryRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ColorRepository $colorRepository
    )
    {
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

    public function productsList(): array
    {
        $products = $this->productRepository->findAll();

        if(!$products) {
            throw new ORMException('aucun produit existant !');
        }

        return $products;

    }

    /**
     * @param $product
     * @param ProductDTO $productDTO
     * @return void
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateProduct(ProductDTO $productDTO, $product)
    {
        $brand = $this->brandRepository->findOneBy(['id' => $productDTO->brand->id]);
        $memory = $this->memoryRepository->findOneBy(['id' => $productDTO->memory->id]);
        $country = $this->countryRepository->findOneBy(['id' => $productDTO->country->id]);
        $user = $this->userRepository->findOneBy(['id' => $productDTO->user->id]);

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

    /**
     * @param $product
     * @return void
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws ORMException
     */
    public function deleteProduct($product)
    {
        $this->productRepository->remove($product);
    }
}
