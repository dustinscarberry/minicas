<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\IdentityProvider;
use App\Entity\ServiceProvider;
use App\Entity\Attribute;
use App\Entity\AttributeMapping;
use App\Service\Factory\AttributeFactory;

class TestFixtures extends Fixture
{
  private $passwordEncoder;
  private $attributeFactory;

  public function __construct(
    UserPasswordHasherInterface $passwordEncoder,
    AttributeFactory $attributeFactory
  )
  {
    $this->passwordEncoder = $passwordEncoder;
    $this->attributeFactory = $attributeFactory;
  }

  public function load(ObjectManager $manager)
  {
    // used for test database

    // create default user
    $user = new User();
    $user->setUsername('demo');
    $user->setPassword($this->passwordEncoder->hashPassword($user, 'demo'));
    $user->setEmail('demo@demo.com');
    $user->setFirstName('Demo');
    $user->setLastName('Demo');
    $user->setRoles(['ROLE_ADMIN']);
    $manager->persist($user);

    // create test attribute
    $attribute = new Attribute();
    $attribute->setHashId('VVEZmx44GBqmG');
    $attribute->setFriendlyName('sAMAccountName');
    $attribute->setAdAttribute('sAMAccountName');
    $manager->persist($attribute);
    $manager->flush();

    // create identity provider
    $idp = new IdentityProvider();
    $idp->setHashId('bJbj6kzj1vMEn');
    $idp->setName('IDP Demo');
    $idp->setType('saml2');
    $idp->setLoginURL('https://login.microsoftonline.com/239ab278-3bba-5t44-b41d-8508a541e025/saml2');
    $idp->setIdentifier('urn:minidas.dev');
    $idp->setCertificate('MIIC1TCCAb2gAwIBAgIJAOXdq1VrzxdEMA0GCSqGSIb3DQEBBQUAMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTAeFw0yMjAxMjAxNDM2MDhaFw0zMjAxMTgxNDM2MDhaMBoxGDAWBgNVBAMTD3d3dy5leGFtcGxlLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAOmvKr1E4MZrzwIh1xFFo5Fa9Ci8IEKhESKuJd8dfqqb7UaYiFPVlgKnrG/mGfPSIfcm1xKZ6g3h2Pf9BjYCD4kTlYDgqOQnZldOtDtiZuIBpm3+yLxT5IanmlAjVN2gtnQ6qpqfIbPPk07/lmQpBvgjRuagMwCgT9NboTPaotTbewwNWzwT88dpypvofQFrXh8MF82SrGTZUCi3lRlGe21Ct9oaGOdyCOYx7CMTvKMqD6IJvYV6m4PH8cl/Zho5B99L6XQ7xlDElzufTCyyRoOPFs6FxBYYRQoHAEQGlh9oTgEQkVT7q2RS1NYC/r+Onu6IgdenepZDhUqyLKezJS8CAwEAAaMeMBwwGgYDVR0RBBMwEYIPd3d3LmV4YW1wbGUuY29tMA0GCSqGSIb3DQEBBQUAA4IBAQCSr7iCSuVJhQuCNaTTRXSuw5WblWQfMK13RNeW8EwjQg+xHjrU1+dU0gkWbjTkEVWsrqZvS2Oy4PnZ0lEdgjAaityZGqyG6yS5EXsRLdUSS/R34YJIc9bhOIeve6UQ/Pn+IWxAlQuXy1lawJnHj2GhqFlzGZrjJBfKq3tBWcTnGvevcgqGtFiXct4fz1rAV5SBHfUe0nvflBOy4xN6czovmDKAAPABWTTu1ZUX5y2NR77stDIg7WZjOCGdCOYb6EchOXNzflZTNbp5YWJdupse5vkIswykxOC4n9744u5P0ZQniZgLnbCR18LS21KwO89CzwdELSImSZzoN2EsnUFD');

    $userAttribute = $this->attributeFactory->getAttribute('VVEZmx44GBqmG');
    $idp->setUserAttributeMapping($userAttribute);

    $manager->persist($idp);

    // create service provider
    $sp = new ServiceProvider();
    $sp->setHashId('w2PwYJXRW4nqZ');
    $sp->setName('SP Demo');
    $sp->setType('cas');
    $sp->setIdentifier('demo.com/cas');
    $sp->setIdentityProvider($idp);

    $spAttributeMapping = new AttributeMapping();
    $spAttributeMapping->setName('DemoName');
    $spAttributeMapping->setAdAttribute($userAttribute);
    $sp->addAttributeMapping($spAttributeMapping);

    $sp->setMatchMethod('exact');

    $manager->persist($sp);

    // flush all to db
    $manager->flush();
  }
}
