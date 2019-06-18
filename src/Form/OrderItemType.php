<?php

namespace App\Form;

use App\Entity\OrderItem;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
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
                    'class' => 'form-control',
                    'placeholder' => 'Quantity'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
            'empty_data' => function (FormInterface $form){
                return new OrderItem($form->getParent()->getParent()->getData());
            }
        ]);
    }
}
