<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\Factory\ServiceProviderFactory;

class ServiceProviderApiController extends ApiController
{

  #[Route('/api/v1/serviceproviders', name: 'getServiceProviders', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function getServiceProviders(ServiceProviderFactory $serviceProviderFactory)
  {
    $serviceProviders = $serviceProviderFactory->getServiceProviders();
    return $this->respond($serviceProviders);
  }

  #[Route('/api/v1/serviceproviders/{hashId}', name: 'deleteServiceProvider', methods: ['DELETE'])]
  #[IsGranted('ROLE_ADMIN')]
  public function deleteServiceProvider(
    $hashId,
    ServiceProviderFactory $serviceProviderFactory
  ) {
    // get service provider
    $serviceProvider = $serviceProviderFactory->getServiceProvider($hashId);

    // check for valid service provider
    if (!$serviceProvider)
      return $this->respondWithErrors(['Invalid data']);

    // delete service provider
    $serviceProviderFactory->deleteServiceProvider($serviceProvider);

    return $this->respond($serviceProvider);
  }
}
