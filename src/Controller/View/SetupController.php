<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Model\Setup;
use App\Model\AppConfig;
use App\Entity\User;
use App\Form\SetupType;
use App\Service\Factory\UserFactory;

class SetupController extends AbstractController
{
  /**
   * @Route("/setup", name="setup")
   */
  public function setup(
    Request $req,
    AppConfig $appConfig,
    UserFactory $userFactory
  )
  {
    // bypass setup once complete
    if ($appConfig->getIsProvisioned())
      return $this->redirect('/dashboard');

    // create setup
    $setup = new Setup();

    // create form object
    $form = $this->createForm(SetupType::class, $setup);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if valid
    if ($form->isSubmitted() && $form->isValid()) {
      // set app settings
      $appConfig->setSiteName($setup->getSiteName());
      $appConfig->setLocale($setup->getLocale());
      $appConfig->setLanguage($setup->getLanguage());
      $appConfig->setSiteTimezone($setup->getSiteTimezone());
      $appConfig->setSessionTimeout($setup->getSessionTimeout());
      $appConfig->setCasTicketTimeout($setup->getCasTicketTimeout());
      $appConfig->setAutoDeleteExpiredSessions($setup->getAutoDeleteExpiredSessions());
      $appConfig->setIsProvisioned(true);
      $appConfig->save();

      // create initial admin user
      $user = new User();
      $user->setUsername($setup->getAdminUsername());
      $user->setFirstName($setup->getAdminFirstName());
      $user->setLastName($setup->getAdminLastName());
      $user->setEmail($setup->getAdminEmail());
      $user->setPassword($setup->getAdminPassword());
      $userFactory->createUser($user);

      return $this->redirect('/dashboard');
    }

    return $this->render('setup.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
