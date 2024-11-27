<?php

namespace App\Form;

use App\Entity\User;
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
                 'mapped' =>false
            ])

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
