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

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;


class AdminProductTechNiqueType extends AbstractType
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
                // 'image' => array(
                //     'field_type' => TextType::class,
                //     'label' => '* Title',
                //     'locale_options' => array(),
                //     'attr' => array('class' => 'form-control images-file', 'onclick'=>"selectFileWithCKFinder(this.id);"),
                //     'constraints' => array(
                //         new NotBlank(array('message' => 'Please enter title')))
                // ),

            ),
            //'exclude_fields' => array('description')
        ));
$builder->add('image', TextType::class, array(
    'attr' => array('class' => 'form-control images-file', 'onclick'=>"selectFileWithCKFinder(this.id);"),

));

	// <button onclick="selectFileWithCKFinder('image');"
    // class="btn btn-default" name="image_btn" id="image_btn" type="button">Browse Server</button>
        // $builder->add('image', CollectionType::class,
        // array(
        //         'entry_type'   => TextType::class,
        //         'entry_options' => array(
        //                 'label' => 'Value',
        //         ),
        //          'label'        => 'Add, move, remove values and press Submit.',
        //         'allow_add' => true,
        //         'allow_delete' => true,
        //         'prototype' => true,
        //         'required' => false,
        //         'attr' => array(
        //                 'class' => 'images-file',
        //         )
        //     ));


        // $builder->add('save_and_add', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        // $builder->add('save_and_edit', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        // $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectBundle\Entity\ProductTechNique'
        ));
    }


}
