<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\Factory\AuthenticatedSessionFactory;

class SessionApiController extends ApiController
{
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
