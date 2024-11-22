<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => "Votre adresse e-mail",
                'attr' => [
                    'placeholder' => "Indiquez votre boite mail"
                ]
            ])

            //->add('roles')

            // ->add('password',PasswordType::class,[
            //     'label' => "Votre mot de passe",
            //     'attr' => [
            //         'placeholder' => "Votre mot de passe",
            //     ]
            // ])

            ->add('plainPassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30,
                        ]),
                ],
                // 'hash_property_path' => 'password',  ->vieux methode a hache le MDP, maintenant on fais par controlleur
                 'first_options'  => [
                    'label' => "Votre mot de passe",
                    'attr' => [
                        'placeholder' => "Votre mot de passe 1",
                 ],
                 ],
                 'second_options' => [
                     'label' => 'Confirmez votre mot de passe',
                     'attr' => [
                         'placeholder' => "Confirmez votre mot de passe 2",
                     ],
                 ],
                'mapped' => false,
                ])

            ->add('firstname',TextType::class,[
                'label' => "Votre prenom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        ]),
                ],
                'attr' => [
                    'placeholder' => "Prenom"
                ]
            ])

            ->add('lastname',TextType::class,[
                'label' => "Nom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        ]),
                ],
                'attr' => [
                    'placeholder' => "Nom"
                ]
            ])

            ->add('submit',SubmitType::class,[
                'label' => "Valider",
                'attr' => [
                    'class' => "btn btn-success" // a chercher sur bootstrap la couleur de button
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'contraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                ])    
            ],
            'data_class' => User::class,
        ]);
    }
}
