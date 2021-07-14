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
    $idp->setCertificate('MIIC8DCCkzEqz/m2PBrVto3F0lUAyeuAdigAwIBAgIQeaS0YxUNjr5Dlc/Uqt0Cav0WLObHqmK7xRz69JJjANBgkqhkiG9w0BAQsFADA0MTIwMAYDVQQDEylNaWNyb3NvZnQgQXp1cmUgRmVkZXJhdGVkIFNTTyBDZXJ0aWZpY2F0ZTAeFw0xOTA2MTIxNTMzNDJaFw0yMjA2MTIxNTMzNDJaMDQxMjAwBgNVBAMTKU1pY3Jvc29mdCBBenVyZSBGZWRlcmF0ZWQgU1NPIENlcnRpZmljYXRlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv6NnUIBiw7wzvbVhdCer58vfe/Op9i9Nw3xbVHmwRrVTo2CBgmgs8nuX1IGmgb/O+MR6HtMJG2rz4szMBXfuI5X0fFB3Lknpbt6xTpqqA9WUC/8CZ9Ffsj9ZAZSuzy2iLdLuA+vLF7ndhDB37PYepTkC9EicbarVtsgyCrEDzhpm/sk4RWjChhM4pWCq0VkJhVaO8gXBT9Pa2oo9UbuFUZfLk31lqrsjipZE855uK83G7AdyUU26fkyYepa7etRIbgleb6jVQwIFLPs358Px0jpTazN5aEnO9eOfylFSYOMWvwADGB3v6jAzgikIHLXwW1uwIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQAZfH9hp35nNH4eqkBaE3KEI8iNWQLSovVj8LwyQQ8mnZduUOkSr17qpU7BHboM37p9kTCuNL31YHSRVFQaOgTzk+eB0hTjVj8bDt7/diIhyqjySPajWhno6Xnb6gZXHem3Gw2p75o7Ebh7nogdKyy2p3GWnZuPagHrL4eA84SLhwtJzwhJvFKtzwZJuSSIb2lFmQEoUryDydzLpCQy/w1yY2Jn+BdGg/CRHhrcjqOdezUsLpzLhnyzeH1Hq4w46G+TPzFG77MXG+TmgUNnCErH2H3xeRY+TW//WpHJjwpDUuDMCOpfU7mggJtcq9P3r');

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
