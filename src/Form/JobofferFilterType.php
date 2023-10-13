<?php

namespace App\Form;

use App\Data\FilterData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Contract;

class JobofferFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ],
                'required' => false,
            ])
            ->add('minSalary', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Salaire minimum'
                ],
                'required' => false,
            ])
            ->add('maxSalary', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Salaire maximum'
                ],
                'required' => false,
            ])
            ->add('contract', EntityType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Type de contrat'
                ],
                'class' => Contract::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
                ->add('city', CityAutocompleteField::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Ville'
                    ],
                    'required' => false,
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'method' => 'GET',

            'csrf_protection' => false,
        ]);
    }
}
