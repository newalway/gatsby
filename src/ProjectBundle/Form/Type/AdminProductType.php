<?php

namespace ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use ProjectBundle\Entity\Series;
use ProjectBundle\Entity\Showroom;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdminProductType extends AbstractType
{
    protected $request_stack;

    public function __construct(RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $local = $this->request_stack->getCurrentRequest()->getLocale();

        $builder->add('translations', 'A2lix\TranslationFormBundle\Form\Type\TranslationsType', array(
            'locales' => array('th'),
            'fields' => array(
                'title' => array(
                    'field_type' => TextareaType::class,
                    'label' => '* Title',
                    'locale_options' => array(),
                    'constraints' => array(
                        new NotBlank(array('message' => 'Please enter title')))
                ),
                'subTitle' => array(
                    'field_type' => TextareaType::class,
                    'label' => 'Subtitle',
                    'locale_options' => array(),

                ),
                'shortDesc' => array(
                    'field_type' => TextareaType::class,
                    'label' => 'Short Description',
                    'locale_options' => array(),

                ),
                'specialFeature' => array(
                    'field_type' => CKEditorType::class,
                    'label' => 'Special Feature',
                    'locale_options' => array(),
                    'attr' => array('rows'=>'5'),
                ),
                'description' => array(
                    'field_type' => CKEditorType::class,
                    'label' => 'Description',
                    'locale_options' => array(),
                    'display' => false
                ),
                'howToUse' => array(
                    'field_type' => CKEditorType::class,
                    'label' => 'How To Use',
                    'locale_options' => array(),
                    'attr' => array('rows'=>'5'),
                ),
                'netWeight' => array(
                    'field_type' => CKEditorType::class,
                    'label' => 'Net Weight / Size / Content(Contain)',
                    'locale_options' => array(),
                    'attr' => array('rows'=>'5'),
                ),
            ),
            //'exclude_fields' => array('description')
        ));

        // $builder->add('price', MoneyType::class, array(
        //                 'attr'      => array('ng-model'=> 'glob_price', 'class'=>''),
        //                 'currency'  => '',
        //                 'scale'     => 2,
        //                 'required'  => true,
        //                 'constraints' => array(
        //                     new NotBlank(array('message' => 'Please enter price')))
        // ));

        $builder->add('price', HiddenType::class, array(
                        'attr'      => array('ng-model'=> 'glob_price', 'class'=>''),
                        'empty_data'      =>'0',
                        'required'  => true,
                        'constraints' => array(
                            new NotBlank(array('message' => 'Please enter price')))
        ));

        $builder->add('compareAtPrice', MoneyType::class, array(
                        'attr'      => array('ng-model'=> 'glob_compare_at_price', 'class'=>''),
                        'currency'  => '',
                        'scale'     => 2,
                        'required'  => false,
        ));

        $builder->add('sku', TextType::class, array(
                        'attr'      => array('ng-model'=> 'glob_sku', 'class' => ''),
                        'required'  => false,
        ));

        // $builder->add('inventoryPolicyStatus', ChoiceType::class, array(
        //                 'attr'      => array('ng-model'=> 'glob_inventory_policy_status'),
        //                 // 'attr'      => array('ng-model'=> 'glob_inventory_policy_status', 'ng-change' => 'changedInventoryPolicyStatus(glob_inventory_policy_status)'),
        //                 'choices'   => array("Don't track inventory" => '0', "Tracks this product's inventory" => '1'),
        //                 'expanded'  => false,
        //                 'multiple'  => false,
        //                 'required'  => true,
        //                 'constraints' => array(
        //                     new NotBlank(array('message' => 'Enter a inventory policy status')))
        // ));

        $builder->add('inventoryPolicyStatus', HiddenType::class, array(
                'attr'      => array('ng-model'=> 'glob_inventory_policy_status'),
                'empty_data' => '0',
        ));

        $builder->add('inventoryQuantity', NumberType::class, array(
                        'attr'      => array('ng-model'=> 'glob_inventory_quantity', 'class' => ''),
                        'required'  => false,
        ));

        // $builder->add('inventoryQuantity', HiddenType::class, array(
        //                 'mapped' => false
        // ));

        $builder->add('weight', NumberType::class, array(
                        'attr'      => array('class' => 'form-control-static'),
                        'required'  => false,
        ));

        $builder->add('weightUnit', ChoiceType::class, array(
                        'attr'      => array(),
                        'choices'   => array("g" => 'g', "ml" => 'ml', "kg" => 'kg', "lb" => 'lb'),
                        'expanded'  => false,
                        'multiple'  => false,
                        'required'  => true
        ));

        $builder->add('series', EntityType::class, array(
            'attr'=>array('class'=>''),
            'label_attr' => array('class' => ''),
            'required' => true,
            // query choices from this entity
            'class' => Series::class,
            'query_builder' => function (EntityRepository $er) {
              return $er->findAllDataJoined();
            },
            'choice_attr' => function($choiceValue, $key, $value) {
                // adds a class like attending_yes, attending_no, etc
                // return ['class' => 'attending_'.strtolower($key)];
                return ['class' => 'series_check_item'];
            },
            // use the User.username property as the visible option string

            'choice_label' => function ($series) {
              $category = $series->getProductCategory()->getTitle();
              return $series->getTitle().' ['.$category.'] ';
            },
            // 'choice_label' => 'translations['.$local.'].title',
            // 'choice_label' => 'title',
            // used to render a select box, check boxes or radios
            'multiple' => false,
            'expanded' => false,
        ));

        // $builder->add('image', TextType::class, array(
        //                 'attr'      => array('class' => ''),
        //                 'required'  => false,
        // ));

        $builder->add('image', HiddenType::class, array(
                        'required'  => false,
        ));

        $builder->add('imageRadarChart', HiddenType::class, array(
                        'required'  => false,
        ));


        $builder->add('video', TextType::class, array(
            'attr' => array('class'=>'form-control'),
            'required' => false,
            // 'constraints' => array(
            //     new NotBlank(array('message' => 'Please enter url')))
        ));

        $builder->add('imageInnerVideo', HiddenType::class, array(
            'attr' => array('class'=>'form-control'),
            'required' => false,
            // 'constraints' => array(
            //     new NotBlank(array('message' => 'Please enter url')))
        ));

        /*
        $builder->add('userWeight', TextType::class, array(
                        'attr'      => array('class' => ''),
                        'required'  => false,
        ));
        */

        /*
        $builder->add('showrooms', EntityType::class, array(
            'attr'=>array('class'=>''),
            'label_attr' => array('class' => ''),
            'required' => false,
            // query choices from this entity
            'class' => Showroom::class,
            'query_builder' => function (EntityRepository $er) {
              return $er->findAllData();
            },
            'choice_attr' => function($choiceValue, $key, $value) {
                // adds a class like attending_yes, attending_no, etc
                // return ['class' => 'attending_'.strtolower($key)];
                return ['class' => 'showroom_check_item'];
            },
            // use the User.username property as the visible option string
            'choice_label' => 'translations['.$local.'].title',
            // 'choice_label' => 'title',
            // used to render a select box, check boxes or radios
            'multiple' => true,
            'expanded' => true, //false for select multiple
        ));
        */

        $builder->add('isNew', CheckboxType::class, array(
                        'label'    => 'Show a New product on the site',
                        'required' => false,
        ));

        $builder->add('status', ChoiceType::class, array(
                        'choices' => array('Available' => '1', 'Unavailable' => '0'),
                        'expanded' => true,
                        'multiple' => false,
                        'label_attr' => array('class' => 'radio-inline'),
                        'constraints' => array(
                            new NotBlank(array('message' => 'Enter a status')))
        ));

        $builder->add('publishDate', DateTimeType::class, array(
                        'required' => true,
                        'input'  => 'datetime',
                        'widget' => 'single_text',
                        'format' => 'YYYY-MM-dd HH:mm',
                        'attr' => array('class' => 'form-control-static'),
                        'constraints' => array(
                            new NotBlank(array('message' => 'Enter a publish date')))
        ));

        // $builder->add('isShowWeight', ChoiceType::class, array(
        //                 'choices' => array('Display' => '1', 'Hide' => '0'),
        //                 'expanded' => true,
        //                 'multiple' => false,
        //                 'label_attr' => array('class' => 'radio-inline'),
        //                 'constraints' => array(
        //                     new NotBlank(array('message' => 'Enter this value')))
        // ));

        $builder->add('isShowWeight', HiddenType::class, array(
                'empty_data' => '0',
                'required' => false,
        ));


        $builder->add('productTechNique', CollectionType::class,
        array(
                'entry_type'   => AdminProductTechNiqueType::class,
                'entry_options' => array(
                        'label' => ' ',
                ),
                'label'        => 'Add, move, remove values and press Submit.',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'attr' => array(
                        'class' => 'collation',
                )
            ));

        $builder->add('save_and_add', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        $builder->add('save_and_edit', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
    }

}
