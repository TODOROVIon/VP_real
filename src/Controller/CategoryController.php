<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        //dd($slug);
        // CategoryRepository sert a -> ouvrir une connexion avec BDD, a ses connecté a la table Category, fais une action en BDD

        $category = $categoryRepository->findOneBySlug($slug);
        // dd($category);

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
