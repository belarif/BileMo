<?php

namespace App\Service;

use App\Entity\DTO\ProductDTO;
use App\Entity\Image;
use App\Entity\Product;
use App\Exception\ProductException;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\CountryRepository;
use App\Repository\MemoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;

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
    ) {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->memoryRepository = $memoryRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->colorRepository = $colorRepository;
    }

    /**
     * @throws ProductException
     */
    public function createProduct(ProductDTO $productDTO): Product
    {
        $brand = $this->brandRepository->findOneBy(['id' => $productDTO->brand->id]);
        $memory = $this->memoryRepository->findOneBy(['id' => $productDTO->memory->id]);
        $country = $this->countryRepository->findOneBy(['id' => $productDTO->country->id]);
        $user = $this->userRepository->findOneBy(['id' => $productDTO->user->id]);

        if ($this->productRepository->findBy(['name' => $productDTO->name])) {
            throw ProductException::ProductExists($productDTO->name);
        }

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

        foreach ($productDTO->getImages() as $productImage) {
            $image = new Image();

            $image->setSrc($productImage->src);
            $product->addImage($image);
        }

        return $this->productRepository->add($product);
    }

    public function productsList(): array
    {
        $products = $this->productRepository->findAll();

        if (!$products) {
            throw ProductException::notProductExists();
        }

        return $products;
    }

    /**
     * @throws ProductException
     */
    public function updateProduct($product, ProductDTO $productDTO): Product
    {
        $brand = $this->brandRepository->findOneBy(['id' => $productDTO->brand->id]);
        $memory = $this->memoryRepository->findOneBy(['id' => $productDTO->memory->id]);
        $country = $this->countryRepository->findOneBy(['id' => $productDTO->country->id]);
        $user = $this->userRepository->findOneBy(['id' => $productDTO->user->id]);

        $product->setDescription($productDTO->description);
        $product->setBrand($brand);
        $product->setMemory($memory);
        $product->setCountry($country);
        $product->setUser($user);

        foreach ($productDTO->getColors() as $color) {
            $product->addColor($this->colorRepository->findOneBy(['id' => $color->id]));
        }

        foreach ($productDTO->getImages() as $productImage) {
            $image = new Image();

            $image->setSrc($productImage->src);
            $product->addImage($image);
        }

        return $this->productRepository->add($product);
    }

    public function deleteProduct($product)
    {
        $this->productRepository->remove($product);
    }
}
