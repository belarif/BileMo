<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/api/products", name="api_create_product", methods={"POST"})
     */
    public function createProduct(Request $request, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $product = $serializer->deserialize($data,Product::class,'json');

        $this->em->persist($product);
        $this->em->flush();

        return new Response('Le produit a été ajouté avec succès',Response::HTTP_CREATED);
    }
}
