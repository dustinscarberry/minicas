<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\AttributeMappingType;
use App\Entity\ServiceProvider;
use App\Entity\IdentityProvider;
use App\Entity\Attribute;
use App\Entity\ServiceCategory;
use App\Service\Factory\ServiceCategoryFactory;

class ServiceProviderType extends AbstractType
{
  private $serviceCategoryFactory;

  public function __construct(ServiceCategoryFactory $serviceCategoryFactory)
  {
    $this->serviceCategoryFactory = $serviceCategoryFactory;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('enabled', CheckboxType::class, [
        'required' => false
      ])
      ->add('name', TextType::class)
      ->add('category', EntityType::class, [
        'class' => ServiceCategory::class,
        'choices' => $this->serviceCategoryFactory->getServiceCategories(),
        'choice_label' => 'title',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'placeholder' => '',
        'required' => false
      ])
      ->add('type', ChoiceType::class, [
        'choices' => [
          'CAS' => 'cas'
        ]
      ])
      ->add('identifier', TextType::class)
      ->add('matchMethod', ChoiceType::class, [
        'label' => 'Match Method',
        'choices' => [
          'Exact' => 'exact',
          'Path' => 'path',
          'Domain' => 'domain'
        ]
      ])
      ->add('contact', TextType::class, [
        'required' => false
      ])
      ->add('notes', TextareaType::class, [
        'required' => false
      ])
      ->add('identityProvider', EntityType::class, [
        'class' => IdentityProvider::class,
        'choice_label' => 'name',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'placeholder' => ''
      ])
      ->add('userAttribute', EntityType::class, [
        'class' => Attribute::class,
        'choice_label' => 'friendlyName',
        'choice_value' => function($entity) {
          return $entity ? $entity->getHashId() : '';
        },
        'required' => false,
        'placeholder' => ''
      ])
      ->add('attributeMappings', CollectionType::class, [
        'entry_type' => AttributeMappingType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => ServiceProvider::class
    ]);
  }
}
