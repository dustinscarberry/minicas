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

    return $this->render('dashboard/home.html.twig', [
      'sessions' => $sessions
    ]);
  }
}
