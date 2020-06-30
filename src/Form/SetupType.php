<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Model\Setup;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SetupType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('adminUsername', TextType::class)
      ->add('adminFirstName', TextType::class)
      ->add('adminLastName', TextType::class)
      ->add('adminEmail', EmailType::class)
      ->add('adminPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'Passwords do no match',
        'first_options' => ['label' => 'Password'],
        'second_options' => ['label' => 'Confirm Password']
      ])
      ->add('siteName', TextType::class)
      ->add('siteTimezone', TimezoneType::class)
      ->add('locale', LocaleType::class)
      ->add('language', LanguageType::class)
      ->add('sessionTimeout', IntegerType::class, [
        'label' => 'Session Timeout (minutes)'
      ])
      ->add('casTicketTimeout', IntegerType::class, [
        'label' => 'CAS Ticket Timeout (minutes)'
      ])
      ->add('autoDeleteExpiredSessions', IntegerType::class, [
        'label' => 'Session Retention Time (days) [0 Never]'
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Setup::class
    ]);
  }
}
