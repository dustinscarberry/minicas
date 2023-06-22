<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\AttributeMapping;
use App\Entity\Attribute;

class AttributeMappingType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'label' => false
      ])
      ->add('adAttribute', EntityType::class, [
        'class' => Attribute::class,
        'choice_label' => 'friendlyName',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'label' => false,
        'placeholder' => ''
      ])
      ->add('transformation', ChoiceType::class, [
        'label' => false,
        'placeholder' => '',
        'choices' => [
          'Uppercase' => 'uppercase',
          'Lowercase' => 'lowercase',
          'Extract Mail Prefix' => 'extractmailprefix',
          'Simplified Groups' => 'simplifiedgroups',
          'Expanded Groups' => 'expandedgroups',
          'Simplified Expanded Groups' => 'simplifiedexpandedgroups'
        ],
        'required' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => AttributeMapping::class
    ]);
  }
}
