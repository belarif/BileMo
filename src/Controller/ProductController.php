<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\CountryRepository;
use App\Repository\MemoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $em;

    private $productRepository;

    private $brandRepository;

    private $memoryRepository;

    private $countryRepository;

    private $userRepository;

    private $colorRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        BrandRepository $brandRepository,
        MemoryRepository $memoryRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        ColorRepository $colorRepository

    )
    {
        $this->em = $entityManager;
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->memoryRepository = $memoryRepository;
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->colorRepository = $colorRepository;
    }

    /**
     * @Route("/products", name="api_create_product", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createProduct(Request $request, SerializerInterface $serializer):JsonResponse
    {

        /**
         * @var ProductDTO $productDTO
         */
        $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

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

        return new JsonResponse('le produit a été créé avec succès','201');
    }
}
