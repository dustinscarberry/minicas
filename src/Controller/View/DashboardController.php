<?php

namespace App\Controller\View;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Manager\AuthenticatedSessionManager;

class DashboardController extends AbstractController
{
  /**
   * @Route("/dashboard", name="dashboardHome")
   */
  public function home(AuthenticatedSessionManager $authSessionManager)
  {
    // get unexpired sessions
    $sessions = $authSessionManager->getSessionsNotExpired();


    // convert data for view
    foreach ($sessions as $session) {
      foreach ($session->getAuthenticatedServices() as $service) {
        $attributes = $service->getAttributes();
        if ($service->getAttributes()->user == '') {
          $attributes->user = $session->getUser();
        }
        $service->setAttributes(json_encode($attributes));
      }
    }

    return $this->render('dashboard/home.html.twig', [
      'sessions' => $sessions
    ]);
  }
}
