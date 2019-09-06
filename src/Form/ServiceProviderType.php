<?php

/*
-----BEGIN CERTIFICATE-----
MIIC8DCCAdigAwIBAgIQeaS0YxUNjr5Dlc/Uz69JJjANBgkqhkiG9w0BAQsFADA0MTIwMAYDVQQD
EylNaWNyb3NvZnQgQXp1cmUgRmVkZXJhdGVkIFNTTyBDZXJ0aWZpY2F0ZTAeFw0xOTA2MTIxNTMz
NDJaFw0yMjA2MTIxNTMzNDJaMDQxMjAwBgNVBAMTKU1pY3Jvc29mdCBBenVyZSBGZWRlcmF0ZWQg
U1NPIENlcnRpZmljYXRlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv6NnUIBiw7wz
vbVhdCer58vfe/Op9i9Nw3xbVHmwRrVTo2CBgmgs8nuX1IGmgb/O+MR6HtMJG2rz4szMBXfuI5X0
fFB3Lknpbt6xTpqqA9WUC/8CZ9Ffsj9ZAZSuzy2iLdLuA+vLF7ndhDB37PYepTkC9EicbarVtsgy
CrEDzhpm/sk4RWjChhM4pWCq0VkJhVaO8gXBT9Pa2oo9UbuFUZqt0Cav0WLObHqmK7xRfLk31lqr
sjipZE855uK83G7AdyUU26fkyYepa7etRIbgleb6jVQwIFLPs358Px0jpTazN5aEnO9eOfylFSYO
MWvwADGB3v6jAzgikIHLXwW1uwIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQAZfH9hp35nNH4eqkBa
E3KEI8iNWQLSovVj8LwyQQ8mnZduUOkSr17qpU7BHboM37p9kTCuNL31YHSRVFQaOgTzk+eB0hTj
Vj8bDt7/diIhyqjySPajWhno6Xnb6gZXHem3Gw2p75o7Ebh7nogdKyy2p3GWnZuPagHrL4eA84SL
hwtJzwhJvFKtzwZJuSSIb2lFmQEoUryDydzLpCQy/w1yY2Jn+BdGg/CRHhrcjqOdezUsLpzLhnyz
eH1Hq4w46G+TPzFG7kzEqz/m2PBrVto3F0lUAyeu7MXG+TmgUNnCErH2H3xeRY+TW//WpHJjwpDU
uDMCOpfU7mggJtcq9P3r
-----END CERTIFICATE-----
*/

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

class ServiceProviderType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('enabled', CheckboxType::class, [
        'required' => false
      ])
      ->add('name', TextType::class)
      ->add('type', ChoiceType::class, [
        'choices' => [
          'CAS' => 'cas'
        ]
      ])
      ->add('identifier', TextType::class)
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
