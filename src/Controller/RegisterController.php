<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
//use App\Controller\RegisterUserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {
        
        //$user = new User();
        //$form = $this->createForm(RegisterUserType::class,$user);

        $form = $this -> createForm(RegisterUserType::class);
        
        return $this -> render('register/index.html.twig',[
            'registerForm' => $form->createView()
        ]);
    }
}


// USE -> je appelle un repertoire
// NAMESPACE -> Je definis un repertoire
