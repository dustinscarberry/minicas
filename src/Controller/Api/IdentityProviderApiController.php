<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Factory\IdentityProviderFactory;

class IdentityProviderApiController extends ApiController
{
  #[Route('/api/v1/identityproviders/{hashId}', name: 'deleteIdentityProvider', methods: ['DELETE'])]
  #[Security("is_granted('ROLE_ADMIN')")]
  public function deleteIdentityProvider(
    $hashId,
    IdentityProviderFactory $identityProviderFactory
  ) {
    // get identity provider
    $identityProvider = $identityProviderFactory->getIdentityProvider($hashId);

    // check for valid identity provider
    if (!$identityProvider)
      return $this->respondWithErrors(['Invalid data']);

    // delete identity provider
    $identityProviderFactory->deleteIdentityProvider($identityProvider);

    return $this->respond($identityProvider);
  }
}
