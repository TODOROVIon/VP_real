<?php

namespace App\Controller;

// USE -> je appelle un repertoire
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(
        Request $request,
         EntityManagerInterface $entityManager,
         UserPasswordHasherInterface $passwordHasher
         
     ): Response
    // $request c'est la variable qui ecoute si notre formulaire est bien remplis
    // $entity manager du doctrine envoi notre formulaire dans la BDD
    {
        $user = new User();

        $form = $this->createForm(RegisterUserType::class, $user);
    // si apres la class on mis pas notre le parametre de user, il n'enregistre
        $form->handleRequest($request);     // => on ecoute notre variable $request avec parametres handleRequest

        if ($form->isSubmitted() && $form->isValid()){
            
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            
            $entityManager->persist($user); //prendre en parametre une objet et creer notre variable dans BDD
            $entityManager->flush(); //pour enregistrer le donnees deja existente
            $this->addFlash(
                'success',
                'Votre compte a etais creer, veuillez vous connecter');

            return $this->redirectToRoute('app_login');
        }
        
        // si le formulaire est soumis, alors tu enregistre dans BDD, tu envoies un message de confirmation du compte bien cree

        return $this -> render('register/index.html.twig',[
            'registerForm' => $form->createView()
        ]);
    }
}


// NAMESPACE -> Je definis un repertoire
