<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Processed' => 1,
                    'Denied' => 0,
                ],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('orderItems', CollectionType::class, [
                'entry_type' => OrderItemType::class,
                'entry_options' => [
                    'label' => false,
                    'required' => true,
                ],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Products',
                'prototype'      => true,

            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
