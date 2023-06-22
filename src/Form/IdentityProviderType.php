<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\IdentityProvider;
use App\Entity\Attribute;

class IdentityProviderType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class)
      ->add('type', ChoiceType::class, [
        'choices' => [
          'SAML2' => 'saml2'
        ]
      ])
      ->add('identifier', TextType::class)
      ->add('loginURL', TextType::class)
      ->add('userAttributeMapping', EntityType::class, [
        'class' => Attribute::class,
        'choice_label' => 'friendlyName',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'placeholder' => ''
      ])
      ->add('certificate', TextareaType::class, [
        'required' => false,
        'attr' => ['spellcheck' => 'false']
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => IdentityProvider::class
    ]);
  }
}
