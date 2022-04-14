<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/products", name="api_create_product", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function createProduct(Request $request, SerializerInterface $serializer):JsonResponse
    {
        $data = $request->getContent();
        $product = $serializer->deserialize($data,Product::class,'json');

        $this->em->persist($product);
        $this->em->flush();

        return new JsonResponse('','201');
    }
}
