<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Model\AppConfig;

class SessionApiController extends ApiController
{
  #[Route('/api/v1/sessions', name: 'getSessions', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function getSessions(
    Request $req,
    SerializerInterface $serializer,
    AuthenticatedSessionFactory $authSessionFactory,
    AppConfig $appConfig
  ) {
    $service = $req->query->get('service');
    $user = $req->query->get('user');
    $timeInterval = $req->query->get('time_interval');
    $expired = $req->query->get('expired') || false;

    // get sessions
    $sessions = $authSessionFactory->getSessionsFiltered(
      $service,
      $user,
      $timeInterval,
      $expired,
      $appConfig->getHideIncompleteSessions()
    );

    $json = $serializer->serialize($sessions, 'json', [
      'groups' => ['session']
    ]);

    return $this->respond($json);
  }

  #[Route('/api/v1/sessions/{hashId}', name: 'getSession', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function getSession(
    $hashId,
    Request $req,
    SerializerInterface $serializer,
    AuthenticatedSessionFactory $authSessionFactory
  ) {
    // get session
    $session = $authSessionFactory->getSession($hashId);

    $json = $serializer->serialize($session, 'json', [
      'groups' => ['sessionDetails']
    ]);

    return $this->respond($json);
  }

  #[Route('/api/v1/sessions/{hashId}', name: 'deleteSession', methods: ['DELETE'])]
  #[IsGranted('ROLE_ADMIN')]
  public function deleteSession(
    $hashId,
    AuthenticatedSessionFactory $sessionManager
  ) {
    // get session
    $session = $sessionManager->getSession($hashId);

    // check for valid session
    if (!$session)
      return $this->respondWithErrors(['Invalid data']);

    // delete session
    $sessionManager->deleteSession($session);

    return $this->respond($session);
  }
}
