<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Model\AppConfig;

class DashboardController extends AbstractController
{
  #[Route('/dashboard', name: 'dashboardHome')]
  public function home(AuthenticatedSessionFactory $authSessionFactory, AppConfig $appConfig)
  {
    // get unexpired sessions
    $sessions = $authSessionFactory->getSessionsNotExpired(
      $appConfig->getHideIncompleteSessions(),
      1000
    );

    // convert data for view
    foreach ($sessions as $session) {
      foreach ($session->getAuthenticatedServices() as $service) {
        $attributes = $service->getAttributes();

        // get user if not overridden
        if ($attributes && $attributes->user == '')
          $attributes->user = $session->getUser();

        // add to attributes
        // check if attributes is not null
        //$attributes->serviceUrl = $service->getReplyTo();
        
        $service->setAttributes(json_encode($attributes));
      }
    }

    return $this->render('dashboard/home.html.twig', [
      'sessions' => $sessions
    ]);
  }

  #[Route('/dashboardx', name: 'dashboardX')]
  public function dashboardX()
  {
    return $this->render('dashboard/homex.html.twig');
  }
}
