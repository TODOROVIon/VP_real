<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
    ): Response
    {
        $user = $this->getUser();
        
        $form = $this->createForm(PasswordUserType::class,$user,[
            'passwordHasher' => $passwordHasher
        ]);
                
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // dd($form);
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                // Hacher le mot de passe avec le passwordHasher
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                
                // Mettre à jour l'entité utilisateur avec le mot de passe haché
                $user->setPassword($hashedPassword);
            }
    

            $this->entityManager->flush();        // avec variable $entityManager->flush, on envoi notre information dans la BDD
           
            $this->addFlash(
                'success',
                'Votre changement passé tres bien!');
        }

        return $this->render('account/password/index.html.twig',[
            'modifyPwd' => $form->createView()
        ]);
    
    }

}


?>