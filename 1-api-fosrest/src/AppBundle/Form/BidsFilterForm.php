<?php

namespace AppBundle\Form;

use AppBundle\Entity\Bid;
use AppBundle\Entity\BidCommodity;
use AppBundle\Entity\Region;
use AppBundle\Model\BidsFilterDTO;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BidsFilterForm extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'target',
                ChoiceType::class,
                [
                    'choices' => [
                        Bid::TARGET_DEMAND,
                        Bid::TARGET_OFFER,
                    ],
                ]
            )
            ->add(
                'commodities',
                EntityType::class,
                [
                    'class' => BidCommodity::class,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository
                            ->createQueryBuilder('entity')
                            ->from('AppBundle:BidCommodity', 'bc')
                            ->where('bc.isActive = true');
                    },
                    'choice_label' => false,
                    'multiple' => true,
                ]
            )
            ->add('name', TextType::class)
            ->add('order', TextType::class)
            ->add('limit', NumberType::class)
            ->add('offset', NumberType::class)
            ->add('dateFrom', DateType::class)
            ->add('dateTo', DateType::class)
            ->add('country', CountryType::class)
            ->add(
                'region',
                EntityType::class,
                [
                    'class' => Region::class,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => BidsFilterDTO::class,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
