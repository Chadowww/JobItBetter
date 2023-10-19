<?php

namespace App\Form;

use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control be-form-input  mb-3', // Ajoute une classe CSS personnalisée
                    'placeholder' => 'Entre ton prénom',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control be-form-input  mb-3', // Ajoute une classe CSS personnalisée
                    'placeholder' => 'Entre ton nom',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control be-form-input  mb-3', // Ajoute une classe CSS personnalisée
                    'placeholder' => 'exemple.email@gmail.com',
                ],
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control be-form-input  mb-3', // Ajoute une classe CSS personnalisée
                    'placeholder' => 'Ajoute ta ville',
                ],
            ])
            ->add('birthday', DateType::class, [
                'required' => false,
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control be-form-input  mb-3', // Ajoute une classe CSS personnalisée
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^0[1-9]\d{8}$/',
                        'message' => 'Le numéro de téléphone n\'est pas valide',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control be-form-input  mb-3', // Ajoute une classe CSS personnalisée
                    'placeholder' => '0123456789',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
