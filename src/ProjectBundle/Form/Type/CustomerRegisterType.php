<?php

namespace ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CustomerRegisterType extends AbstractType
{
    protected $options = array(
        'data_class' => 'ProjectBundle\Entity\User',
        'name'       => 'user',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      /*
      $bd_this_year = date('Y');
      $bd_start_year = 1920;

      $builder->add('email', TextType::class, array(
                   'attr' => array('class'=>'form-control dark'),
                   'required' => true,
                   'constraints' => array(
                       new NotBlank(array('message' => 'error.email')),
                       new Email(array('message' => 'error.email.wrong')))
      ));

      $builder->add('plainPassword', RepeatedType::class, array(
                      'attr' => array('class' => 'form-control dark'),
                      'required' => true,
                      'type' => PasswordType::class,
                      'options' => array('translation_domain' => 'FOSUserBundle'),
                      'first_options' => array('label' => 'member.password', 'attr'=> array('class'=>"form-control dark")),
                      'second_options' => array('label' => 'member.password.confirm', 'attr'=> array('class'=>"form-control dark")),
                      'invalid_message' => 'fos_user.password.mismatch',
                      'constraints' => array(
                          new NotBlank(array('message' => 'error.password')))
      ));

      $builder->add('gender', ChoiceType::class, array(
                      'required' => true,
                      'choices' => array('member.personal.male' => 'M', 'member.personal.female' => 'F'),
                      'expanded'  => true,
                      'multiple'  => false,
                      'constraints' => array(
                        new NotBlank(array('message' => 'error.gender')),
      )));

      $builder->add('birth_date', DateType::class, array(
                    'required' => false,
                    'widget' => 'choice',
                    'years' => range($bd_this_year, $bd_start_year),
                    'attr' => array('class'=>'custom'),
      ));

      $builder->add('first_name', TextType::class, array(
                      'attr' => array('class'=>'form-control dark',),
                      'required' => true,
                      'constraints' => array(
                          new NotBlank(array('message' => 'error.firstname')))
      ));

      $builder->add('last_name', TextType::class, array(
                      'attr' => array('class'=>'form-control dark',),
                      'required' => true,
                      'constraints' => array(
                          new NotBlank(array('message' => 'error.lastname')))
      ));

      $builder->add('phone_number', TextType::class, array(
                      'required' => true,
                      'attr' => array('class'=>'form-control dark'),
      ));

      $builder->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-lg btn-primary')));
      */
    }

}
