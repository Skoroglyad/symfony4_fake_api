<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Tariff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email')
                ->add('last_name', null, [
                    'property_path' => 'lastName'
                ])
                ->add('amount')
                ->add('skId')
                ->add('idPayment')

                ->add('tariff_id', EntityType::class, [
                   'property_path' => 'tariff',
                   'class' => Tariff::class
                ]);
        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection'   => false,
            'data_class' => Order::class
        ));
    }
}
