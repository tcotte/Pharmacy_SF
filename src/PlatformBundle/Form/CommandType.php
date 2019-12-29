<?php

namespace PlatformBundle\Form;

use PlatformBundle\Entity\Command;
use PlatformBundle\Entity\CommandProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PlatformBundle\Repository\ProductRepository;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commandProducts', CollectionType::class, [
            'entry_type'   => CommandProductType::class,
            'allow_add' => true,
            'prototype' => true,
            'prototype_name' => '__proto_commandProduct__',
            ])
            ->add('comment',  TextareaType::class, [
                'label' => 'Commentaire :',
                'attr' => [
                    'class' => 'col-md-6 comment'
                ],
                'required'=>false
            ])
            ->add('Valider', SubmitType::class);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Command::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'platformbundle_command';
    }
}
