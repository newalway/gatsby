<?php

namespace ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;


class AdminShippingRateType extends AbstractType
{
    const RATE_TYPE_OPTION = array(
        'Price Based Rate'    => 'price_based_rate',
        'Weight Based Rate'   => 'weight_based_rate',
        'Item Based Rate'   => 'item_based_rate'
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('rateType', ChoiceType::class, array(
            'attr' => array('ng-model'=>'rate_type','class'=>'form-control'),
            'choices'  => self::RATE_TYPE_OPTION,
        ));

        $builder->add('title', TextType::class, array(
            'attr' => array(
            'class'=>'form-control',
            'errortext'=>'Enter a title'),
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'Enter a title')),
             )
         ));

         $builder->add('minimumRange', NumberType::class, array(
            'attr' => array(
            'class'=>'form-control',
            'errortext'=>'Enter a minimum range'),
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'Enter a minimum range')),
             )
         ));
         $builder->add('maximumRange', NumberType::class, array(
            'attr' => array(
            'class'=>'form-control',
            'errortext'=>'Enter a maximum range'),
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'Enter a maximum range')),
              )
          ));
          $builder->add('rateAmount', NumberType::class, array(
            'attr' => array(
            'class'=>'form-control',
            'errortext'=>'Enter a rate amount'),
            'required' => false,
            // 'constraints' => array(
            //     new NotBlank(array('message' => 'Enter a rate amount')),
            //   )
          ));

          $builder->add('freeShipping', CheckboxType::class, array(
                          'label'    => 'Free shipping rate',
                          'required' => false,
          ));

          $builder->add('save_and_add', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
          $builder->add('save_and_edit', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
          $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectBundle\Entity\ShippingRate',
            'name'       => 'shipping_rates',
        ));
    }
}
