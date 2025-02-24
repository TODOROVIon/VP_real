<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/produit/{slug}', name: 'app_product')]

    /*public function index(#[MapEntity(slug: 'slug')] Product $product): Response      une maniere AutoMaping existante pour gagné dans la ligne de code       //#[MapEntity(slug: 'slug') -> annotation
    {

        if(!$product){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
    */

    public function index($slug,ProductRepository $productRepository ): Response
    {
        $product = $productRepository->findOneBySlug($slug);
        // dd($product);

        if(!$product){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
