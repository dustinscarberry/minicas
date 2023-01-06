<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Model\AppConfig;

class AnalyticsController extends AbstractController
{
  #[Route('/dashboard/analytics')]
  public function analytics(AuthenticatedSessionFactory $authSessionFactory, AppConfig $appConfig)
  {
    // get unexpired sessions
    $sessions = $authSessionFactory->getSessionsNotExpired(
      $appConfig->getHideIncompleteSessions(),
      500
    );

    $authenticatedServiceStats = [];

    foreach ($sessions as $session) {
      foreach ($session->getAuthenticatedServices() as $service) {
        $serviceName = $service->getService()->getName();

        foreach ($authenticatedServiceStats as $key => $serviceCounter) {
          if ($serviceCounter['name'] == $serviceName) {
            $authenticatedServiceStats[$key]['activeSessions']++;
            continue 2;
          }
        }

        // add service to count if not found
        $authenticatedServiceStats[] = [
          'name' => $serviceName,
          'activeSessions' => 1
        ];
      }
    }

    return $this->render('dashboard/analytics/view.html.twig', [
      'authenticatedServiceStats' => $authenticatedServiceStats
    ]);
  }
}
