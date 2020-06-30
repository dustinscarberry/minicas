<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Setting;
use App\Entity\Attribute;

class AppFixtures extends Fixture
{
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
    $this->passwordEncoder = $passwordEncoder;
  }

  public function load(ObjectManager $manager)
  {
    // used for test database population

    // create default users
    $user = new User();
    $user->setUsername('demo');
    $user->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
    $user->setEmail('demo@demo.com');
    $user->setFirstName('Demo');
    $user->setLastName('Demo');
    $user->setRoles(['ROLE_ADMIN']);
    $manager->persist($user);

    $manager->flush();
  }
}
