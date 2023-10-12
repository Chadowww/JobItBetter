<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CvthequeType extends AbstractType
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categories = $this->registry->getRepository(Category::class)->findAll();

        foreach ($categories as $category) {
            $builder->add('category_' . $category->getId(), CheckboxType::class, [
                'label' => $category->getName(),
                'required' => false,
                'mapped' => false,
                'disabled' => true,
                'attr' => [
                    'class' => 'category-header',
                ],
            ]);

            // Récupérez les technologies pour cette catégorie
            $technologies = $category->getTechnologies();

            foreach ($technologies as $technology) {
                $builder->add($technology->getSlug(), CheckboxType::class, [
                    'label' => $technology->getName(),
                    'required' => false,
                ]);
            }
        }
            $builder->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
