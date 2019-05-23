<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\TypeProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdBy')
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('stock')
            ->add('reference')
            ->add('provider')
            ->add('price', NumberType::class)
            ->add('type', EntityType::class, [
                'class' => TypeProduct::class,
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
