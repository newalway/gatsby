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

class AdminSearchStylingType extends AbstractType
{
    protected $container;
    protected $request_stack;
    protected $em;

    public function __construct($kernel, RequestStack $request_stack,EntityManagerInterface $em)
    {
        $this->request_stack = $request_stack;
        $this->container = $kernel->getContainer();
        $this->em = $em;
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

        // $builder->add('productCategory', EntityType::class, array(
        //     'attr'=>array('class'=>'form-control'),
        //     'label_attr' => array('class' => ''),
        //     'required' => false,
        //     // query choices from this entity
        //     'class' => EventCategory::class,
        //     'query_builder' => function (EntityRepository $er) {
        //         return $er->findAllData();
        //     },
        //     'choice_attr' => function($choiceValue, $key, $value) {
        //         // adds a class like attending_yes, attending_no, etc
        //         return ['class' => 'template_customer_group_item'];
        //         // return ['class' => 'attending_'.strtolower($key)];
        //         // return ['class' => 'level_'.$choiceValue->getLvl()];
        //     },
        //     // use the User.username property as the visible option string
        //     'choice_label' => 'translations['.$local.'].title',
        //     // 'choice_label' => 'title',
        //     // used to render a select box, check boxes or radios
        //     'multiple' => false,
        //     'expanded' => false, //false for select multiple
        // ));


        $builder->add('search', SubmitType::class, array('attr' => array( 'class' => 'btn btn-primary') ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

        $builder->setMethod('GET');
    }

    protected function addElements(FormInterface $form,ProductCategory $category = null) {
        // 4. Add the province element
        $form->add('productCategory', EntityType::class, array(
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

        // $series empty, unless there is a selected category (Edit View)
        $series_result = array();

        // If there is a city stored in the Person entity, load the neighborhoods of it
        if ($category) {
            // Fetch Neighborhoods of the City if there's a selected city
            // $series_result = $this->em->getRepository(Series::class)->findDataByCategory($category->getId())
            //             ->getQuery()
            //             ->getResult();
            // Add the serie field with the properly data
            $catId =$category->getId();
            $form->add('series', EntityType::class, array(
                'required' => false,
                'placeholder' => 'Select a category first ...',
                'class' => Series::class,
                'query_builder' => function (EntityRepository $er) use ($catId) {
                  return $er->findDataByCategory($catId);
                },
                'choice_label' => function ($productCategory) {
                    return (string) $productCategory->getTitle();
                },
            ));
        }else{
            // Add the serie field with the properly data
            $form->add('series', EntityType::class, array(
                'required' => false,
                'placeholder' => 'Select a category first ...',
                'class' => Series::class,
                'choices' =>  $series_result
            ));
        }


    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected category and convert it into an Entity
        $category = $this->em->getRepository(ProductCategory::class)->find($data['productCategory']);

        $this->addElements($form, $category);
    }

    function onPreSetData(FormEvent $event) {
        $video = $event->getData();
        $form = $event->getForm();
        // dump($form);
        // exit;

        // When you create a new banner, the category is always empty
        if($video){
            $category = $video->getProductCategory() ? $video->getProductCategory() : null;
        }else{
            $category= null;
        }


        $this->addElements($form, $category);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }


}
