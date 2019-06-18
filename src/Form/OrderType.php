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
            ->add('ordersHasProducts', CollectionType::class, [
                'entry_type' => OrdersHasProductsType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'label' => 'Products'
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
