<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    // racine ou homepage de notre page besoin d'etre toujour '/'
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
