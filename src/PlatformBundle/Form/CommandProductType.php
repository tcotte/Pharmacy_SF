<?php

namespace PlatformBundle\Form;

use PlatformBundle\Entity\CommandProduct;
use PlatformBundle\Entity\Product;
use PlatformBundle\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, array(
                'class' => Product::class,
                'choice_label' => 'designation',
                'placeholder' => '',
                'label' => false,
                'attr' => [
                    'class' => 'product-select hidden'
                ]
            ))
            ->add('quantity',   IntegerType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                    'min' => 0,
                    'class' => 'quantity-select'
                ],
                'label' => false,
            ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PlatformBundle\Entity\CommandProduct'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'platformbundle_commandproduct';
    }


}
