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

use ProjectBundle\Entity\ProductCategory;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;


use ProjectBundle\Entity\Series;

class AdminSearchCategoryType extends AbstractType
{
    protected $container;
    protected $request_stack;

    public function __construct($kernel, RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
        $this->container = $kernel->getContainer();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $local = $this->request_stack->getCurrentRequest()->getLocale();

        $builder->add('q', TextType::class, array(
            'required' => false,
        ));

        $builder->add('status', ChoiceType::class, array(
            'choices' => array('Publish' => '1', 'Unpublish' => '0'),
            'expanded' => true,
            'multiple' => false,
            'attr' => array('class' => 'form-control-static'),
        ));

        $builder->add('productCategory', EntityType::class, array(
            'attr'=>array('class' => ''),
            'label_attr' => array('class' => ''),
            'required' => false,
            'placeholder' => 'Select a category ...',
            // query choices from this entity
            'class' => ProductCategory::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->findAllData();
            },
            'choice_label' => function ($productCategory) {
                return (string) $productCategory->getTitle();
            },
            // used to render a select box, check boxes or radios
            'multiple' => false,
            'expanded' => false,
        ));


        $builder->add('search', SubmitType::class, array('attr' => array( 'class' => 'btn btn-primary') ));

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        // $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

        $builder->setMethod('GET');
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }


}
