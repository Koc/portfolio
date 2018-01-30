<?php

namespace AppBundle\Form;

use AppBundle\Entity\Bid;
use AppBundle\Entity\BidCommodity;
use AppBundle\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'direction',
                ChoiceType::class,
                [
                    'choices' => [
                        Bid::TARGET_DEMAND,
                        Bid::TARGET_OFFER,
                    ],
                ]
            )
            ->add(
                'commodity',
                EntityType::class,
                [
                    'class' => BidCommodity::class,
                ]
            )
            ->add(
                'unit',
                EntityType::class,
                [
                    'class' => Unit::class,
                ]
            )
            ->add('priceMin')
            ->add('priceMax')
            ->add('currency', CurrencyType::class)
            ->add('conditions');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Bid::class,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            ]
        );
    }


    public function getBlockPrefix()
    {
        return null;
    }
}
