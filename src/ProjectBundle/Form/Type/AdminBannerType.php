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

use ProjectBundle\Entity\Banner;
use ProjectBundle\Entity\ProductCategory;
use ProjectBundle\Entity\Series;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Validator\Constraints\Image;

class AdminBannerType extends AbstractType
{
        /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                'subtitle' => array(
                    'field_type' => TextareaType::class,
                    'label' => 'Subtitle',
                    'locale_options' => array()
                ),
                'website' => array(
                    'field_type' => TextType::class,
                    'label' => 'Link',
                    'locale_options' => array(),
                    'attr' => array('placeholder' => 'https://www.gatsbythailand.com/'),
                ),
                'image' => array(
                    'field_type' => TextType::class,
                    'label' => 'Image',
                    'locale_options' => array(),
                    'attr' => array('class' => 'image-input-group'),
                    'required' => true,
                ),
                'imageMobile' => array(
                    'field_type' => TextType::class,
                    'label' => 'Image Mobile',
                    'locale_options' => array(),
                    'attr' => array('class' => 'image-input-group'),
                    'required' => true,
                ),
            )
        ));
        //
        // $builder->add('image', TextType::class, array(
        //                 'attr' => array('class' => 'form-control'),
        //                 'required' => false,
        // ));
        //
        // $builder->add('image_mobile', TextType::class, array(
        //                 'attr' => array('class' => 'form-control'),
        //                 'required' => false,
        // ));

        $builder->add('public_date', DateType::class, array(
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


        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form,ProductCategory $category = null) {
        // 4. Add the province element
        $form->add('productCategory', EntityType::class, array(
            'required' => true,
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
            $catId=$category->getId();
            // Add the serie field with the properly data
            $form->add('series', EntityType::class, array(
                'required' => true,
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
                'required' => true,
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
        $banner = $event->getData();
        $form = $event->getForm();

        // When you create a new banner, the category is always empty
        $category = $banner->getProductCategory() ? $banner->getProductCategory() : null;

        $this->addElements($form, $category);
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectBundle\Entity\Banner'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projectbundle_banner';
    }

}
