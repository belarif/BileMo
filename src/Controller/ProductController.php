<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\CountryRepository;
use App\Repository\MemoryRepository;
use App\Repository\ProductRepository;
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

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        BrandRepository $brandRepository,
        MemoryRepository $memoryRepository,
        CountryRepository $countryRepository
    )
    {
        $this->em = $entityManager;
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->memoryRepository = $memoryRepository;
        $this->countryRepository = $countryRepository;
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
        $jsonData = $request->getContent();
        $objectData = json_decode($jsonData,true);

        $brandId = $objectData["brand"]["id"];
        $brand = $this->brandRepository->findOneBy(['id' => $brandId]);

        $memoryId = $objectData["memory"]["id"];
        $memory = $this->memoryRepository->findOneBy(['id' => $memoryId]);

        $countryId = $objectData["country"]["id"];
        $country = $this->countryRepository->findOneBy(['id' => $countryId]);

        $product = $serializer->deserialize($jsonData,Product::class,'json');

        $product->setBrand($brand);
        $product->setMemory($memory);
        $product->setCountry($country);

        $this->productRepository->add($product);

        return new JsonResponse('le produit a été créé avec succès','201');
    }
}
