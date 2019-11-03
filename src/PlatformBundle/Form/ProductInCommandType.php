<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PlatformBundle\Repository\ProductRepository;


class ProductInCommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // $category = $options['data']['category'];
        $builder
            ->add('products', EntityType::class, array(
                'class' => 'PlatformBundle:Product',
                'choice_label' => 'designation',
                'placeholder' => '',
                // 'query_builder' => function (ProductRepository $er) use ($category) {
                //     return $er->getCategoryOfProduct($category);
                // },
            ))
            ->add('quantity',   IntegerType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                    'min' => 1,
                ]
            ]);
        // ->add('save',      SubmitType::class);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ProductInCommandType';
    }
}
