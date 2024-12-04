<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword',PasswordType::class,[
                'label'=>"Votre mot de passe actuel",
                'attr' => [
                    'placeholder' => "Votre mot de passe actuel. ",
                ],
                'mapped' =>false,
            ])

            ->add('plainPassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30,
                    ]),
                ],
                'first_options'  => [
                    'label' => "Votre mot de passe",
                    'attr' => [
                        'placeholder' => "Votre nouveau mot de passe. ",
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => "Confirmez votre nouveau mot de passe. ",
                    ],
                ],
                'mapped' => false,
                ])

                ->add('submit',SubmitType::class,[
                    'label' => "Mettre a jour mon mot de passe",
                    'attr' => [
                        'class' => "btn btn-success" // a chercher sur bootstrap la couleur de button
                    ]
                ])

                ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
                    $form = $event->getForm();  // recuperation de notre formulaire
                    $user = $form->getConfig()->getOptions()['data'];
                    $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                    
                    // 1. Recuperer le mot de passe saisi par l'utilisateur
                    $isValid = $passwordHasher->isPasswordValid(
                        $user,
                        $form->get('actualPassword')->getData()
                    );
                    // dd($isValid);
                    
                    // 2. Recuperer le mot de passe actuel en BDD
                    // $actualPwdDatabase = $user->getPassword();  // une modalité, plus haut on recupér et comparé notre MDP
                            // dump($actualPwd); // affiche notre MDP en clair
                            // dd($actualPwdDatabase); // affiche notre MDP crypte importé du notre base de donnees

                    // 3. Si c'est != envoyer une erreur
                    if (!$isValid){
                        $form->get('actualPassword')->addError(new FormError('Votre Mot De Passe actuel n\'est pas conforme'));
                    }
                })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
