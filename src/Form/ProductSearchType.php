<?php

namespace App\Form;

use App\Entity\ProductSearch;

use App\Entity\TypeProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => TypeProduct::class,
                'placeholder' => 'Choose type',
                'required' => false
            ])
            ->add('stocked', ChoiceType::class, [
                'choices' => [
                    'In Stock' => true,
                    'Out of stock' => false,
                ],
                'placeholder' => 'Stock',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    /**
     * return clean prefix in the url
     */
    public function getBlockPrefix()
    {
        return '';
    }
}