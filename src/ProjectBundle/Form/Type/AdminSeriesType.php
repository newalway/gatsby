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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ProjectBundle\Entity\ProductCategory;
use Doctrine\ORM\EntityRepository;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdminSeriesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

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
                    'label' => ' Subtitle',
                    'locale_options' => array(),
                    'display' => false,
                ),
                'shortDesc' => array(
                    'field_type' => CKEditorType::class,
                    'label' => ' Short Description',
                    'locale_options' => array(),
                    'display' => false,
                ),
                'description' => array(
                    'field_type' => CKEditorType::class,
                    'label' => ' Description',
                    'locale_options' => array(),
                    'display' => false,
                ),
            ),
            // 'exclude_fields' => array('shortDesc')
        ));

        $builder->add('image', TextType::class, array(
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
        ));

        $builder->add('imageBanner', TextType::class, array(
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
        ));

        $builder->add('imageBannerMobile', TextType::class, array(
                        'attr' => array('class' => 'form-control'),
                        'required' => false,
        ));
        $builder->add('template', ChoiceType::class, array(
            'attr'=>array('class' => ''),
            'label_attr' => array('class' => ''),
            'required' => true,
            'choices'  => [
                'Default template' => 'default',
                'Moving rubber template' => 'movingRubber',
                'Styling Pomade template' => 'stylingPomade',
                'Hair Jam template' => 'hairJam',
                'Hair Spray template' => 'hairSpray',
            ],
            // used to render a select box, check boxes or radios
            'multiple' => false,
            'expanded' => false,
        ));

        $builder->add('productCategory', EntityType::class, array(
            'attr'=>array('class' => ''),
            'label_attr' => array('class' => ''),
            'required' => true,
            // query choices from this entity
            'class' => ProductCategory::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->findAllData();
            },
            // use the User.username property as the visible option string
            'choice_label' => function ($productCategory) {
                return $productCategory->getTitle();
            },
            // used to render a select box, check boxes or radios
            'multiple' => false,
            'expanded' => false,
        ));

        $builder->add('save_and_add', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        $builder->add('save_and_edit', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
    }

}
