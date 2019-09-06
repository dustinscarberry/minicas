<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\AttributeMapping;
use App\Entity\Attribute;

class AttributeMappingType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
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
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => AttributeMapping::class
    ]);
  }
}
