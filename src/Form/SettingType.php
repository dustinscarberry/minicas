<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Model\AppConfig;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SettingType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('siteName', TextType::class)
      ->add('hideIncompleteSessions', CheckboxType::class, [
        'required' => false
      ])
      ->add('ignoreSigningCertExpiration', CheckboxType::class, [
        'required' => false
      ])
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

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => AppConfig::class,
      'csrf_protection' => false
    ]);
  }
}
