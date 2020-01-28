<?php

namespace App\Form;

use App\Entity\Tariff;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TariffFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('option', null, [
                    'apply_filter'  => function (QueryInterface $filterQuery, $field, $values) {
                        if ($values['value']) {
                            $query = $filterQuery->getQueryBuilder();
                            $query->andWhere($field.' = (:option)')
                                ->setParameter('option', $values['value']);
                        }
                    }
                ])
            ;
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'method' => 'GET',
            'validation_groups' => array('filtering'),
            'data_class' => Tariff::class
        ));
    }
}
