<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Votre prenom',
                'attr' => [
                    'placeholder' => 'Indiquez votre prenom',
                ],
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Indiquez votre nom',
                ],
            ])
            ->add('address', TextType::class,[
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Indiquez votre adresse',
                ],
            ])
            ->add('postal', TextType::class,[
                'label' => 'Votre code postal',
                'attr' => [
                    'placeholder' => 'Indiquez votre code postal',
                ],
            ])
            ->add('city', TextType::class,[
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Indiquez votre ville',
                ],
            ])
            ->add('country',CountryType::class,[
                'label' => 'Votre Pays ...',
            ])
            ->add('phone', TextType::class,[
                'label' => 'Votre telephone',
                'attr' => [
                    'placeholder' => 'Indiquez votre numero de telephone ...',
                ],
            ])
            ->add('submit',SubmitType::class,[
                'label' => "Sauvegarder",
                'attr' => [
                    'class' => "btn btn-success" // a chercher sur bootstrap la couleur de button
                ]
            ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'firstname',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
