<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $entityManager; /*declaration de parametre*/


    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

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

        return $this->render('account/password.html.twig',[
            'modifyPwd' => $form->createView()
        ]);
    
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }

    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function addressDelete(Request $request, $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        if (!$address OR $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_addresses');
        }

        $this->addFlash(
            'success',
            'Votre adresse est correctement supprimer!');

        $this->entityManager->remove($address);
        
        $this->entityManager->flush();

        return $this->redirectToRoute('app_account_addresses');
    }
    
    #[Route('/compte/adresses/ajouter/{id}', name: 'app_account_address_form', defaults:['id'=>null])]
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
    {
        if ($id){
            $address = $addressRepository->findOneById($id);
            if (!$address OR $address->getUser() != $this->getUser()){
                return $this->redirectToRoute('app_account_addresses');
            }
        } else {
            $address = new Address();
            $address->setUser($this->getUser());    /*on enregistre notre user depuis entity user et on prendre le ID de user*/
        }

        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            
            $this->addFlash(
                'success',
                'Votre est correctement sauvegarder!');
                
            return $this->redirectToRoute("app_account_addresses");
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

}
