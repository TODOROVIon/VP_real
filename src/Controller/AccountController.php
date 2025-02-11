<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
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

    /*
    Route pour modifier mot de pass
    */
    #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
    public function password(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
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
    

            $entityManager->flush();        // avec variable $entityManager->flush, on envoi notre information dans la BDD
           
            $this->addFlash(
                'success',
                'Votre changement passé tres bien!');
        }

        return $this->render('account/password.html.twig',[
            'modifyPwd' => $form->createView()
        ]);
    
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }
    
    #[Route('/compte/adresses/ajouter', name: 'app_account_address_form')]
    public function addressForm(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $form,
        ]);
    }

}
