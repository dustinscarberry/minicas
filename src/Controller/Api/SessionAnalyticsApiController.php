<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Model\AppConfig;

class SessionAnalyticsApiController extends ApiController
{
  #[Route('/api/v1/sessionanalytics/services', name: 'getSessionAnalyticsServices', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function getSessionAnalyticsServices(
    Request $req,
    AuthenticatedSessionFactory $authSessionFactory,
    AppConfig $appConfig
  ) {
    // get sessions filtered by time
    $timeInterval = $req->query->get('time_interval');

    // get sessions
    $sessions = $authSessionFactory->getSessionsFiltered(
      null,
      $timeInterval,
      true,
      $appConfig->getHideIncompleteSessions()
    );

    $serviceAnalytics = [];
    foreach ($sessions as $session) {
      foreach ($session->getAuthenticatedServices() as $service) {
        $serviceName = $service->getService()->getName();

        foreach ($serviceAnalytics as $key => $serviceCounter) {
          if ($serviceCounter['name'] == $serviceName) {
            $serviceAnalytics[$key]['sessions']++;
            continue 2;
          }
        }

        // add service to count if not found
        $serviceAnalytics[] = [
          'name' => $serviceName,
          'sessions' => 1
        ];
      }
    }

    return $this->respond($serviceAnalytics);
  }

  #[Route('/api/v1/sessionanalytics/overall', name: 'getSessionAnalyticsOverall', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function getSessionAnalyticsOverall(
    Request $req,
    AuthenticatedSessionFactory $authSessionFactory,
    AppConfig $appConfig
  ) {
    // get sessions filtered by time
    $timeInterval = $req->query->get('time_interval');

    // get sessions
    $sessions = $authSessionFactory->getSessionsFiltered(
      null,
      $timeInterval,
      true,
      $appConfig->getHideIncompleteSessions()
    );

    $overallAnalytics = null;

    // get total sessions for time period
    // get uniquie users for time period
    





    return $this->respond($overallAnalytics);
  }
}
