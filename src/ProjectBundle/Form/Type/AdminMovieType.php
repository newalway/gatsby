<?php

namespace ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use ProjectBundle\Entity\Movie;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;



use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdminMovieType extends AbstractType
{
    protected $container;
    protected $request_stack;
    protected $em;

    public function __construct($kernel, RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
        $this->container = $kernel->getContainer();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $local = $this->request_stack->getCurrentRequest()->getLocale();
// dump($options['data']);
// exit;
        $builder->add('translations', 'A2lix\TranslationFormBundle\Form\Type\TranslationsType', array(
            'locales' => array('th'),
            'fields' => array(
                'title' => array(
                    'field_type' => TextType::class,
                    'label' => '* Title',
                    'locale_options' => array(),
                    'constraints' => array(
                        new NotBlank(array('message' => 'Please enter title')))
                ),
            ),
            //'exclude_fields' => array('description')
        ));

        $builder->add('image', HiddenType::class, array(
            'required'  => false,
        ));

        $builder->add('video', TextType::class, array(
            'attr' => array('class'=>'form-control'),
            'required' => false,
            // 'constraints' => array(
            //     new NotBlank(array('message' => 'Please enter url')))
        ));

        $builder->add('publicDate', DateType::class, array(
            'required' => true,
            'input'  => 'datetime',
            'widget' => 'single_text',
            'attr' => array('class' => 'form-control-static'),
            'constraints' => array(
                new NotBlank(array('message' => 'Enter a publish date')))
        ));

        $builder->add('status', ChoiceType::class, array(
            'choices' => array('Publish' => '1', 'Unpublish' => '0'),
            'expanded' => true,
            'multiple' => false,
            'attr' => array('class' => 'form-control-static'),
            'constraints' => array(
                new NotBlank(array('message' => 'Enter a status')))
        ));



        $builder->add('save_and_add', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        $builder->add('save_and_edit', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
        $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));

    }

}
