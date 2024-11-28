<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
    public function password(Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(PasswordUserType::class);
        
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $hashedPassword = $passwordHasher->hashPassword(
            dd($form->getData()),
            $form->get('actualPassword')->getData()
            );
        }

        return $this->render('account/password.html.twig',[
            'modifyPwd' => $form->createView()
        ]);
    }
    // #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
    // public function password(
    //     Request $request,
    //     UserPasswordHasherInterface $passwordHasher,
    //     EntityManagerInterface $entityManager,
    //     Security $security
    // ): Response {
    //     $user = $security->getUser(); // Récupérer l'utilisateur connecté
    //     $form = $this->createForm(PasswordUserType::class);
    //     // $form->get('actualPassword')->getData();

    //     $form->handleRequest($request);
    //     // dd($form);
    //     // dd($form->get('newPassword')->getData());
        
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $actualPassword = $form->get('actualPassword')->getData();
    //         $newPassword = $form->get('newPassword')->getData();

            
    //         // Vérifie si l'ancien mot de passe est correct
    //         if (!$passwordHasher->isPasswordValid($user, $actualPassword)) {
    //             dd($user);
    //             $this->addFlash('error', 'L\'ancien mot de passe est incorrect.');
    //             return $this->redirectToRoute('app_account_modify_pwd');
    //         }
    
    //         // Hash le nouveau mot de passe
    //         $hashedPassword = $passwordHasher->hashPassword(
    //             $user,
    //             $newPassword
    //         );
    
    //         $user->setPassword($hashedPassword); // Met à jour le mot de passe de l'utilisateur
    //         $entityManager->flush(); // Enregistre les changements dans la base de données
    
    //         $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
    
    //         return $this->redirectToRoute('app_account');
    //     }
    
        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView(),
        ]);
    }
    

}
