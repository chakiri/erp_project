<?php

namespace App\Form;

use App\Entity\OrdersHasProducts;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersHasProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('quantity',null, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrdersHasProducts::class,
            'empty_data' => function (FormInterface $form){
                return new OrdersHasProducts($form->getParent()->getParent()->getData());
            }
        ]);
    }
}
