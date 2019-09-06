<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Manager\ServiceProviderManager;

class ServiceProviderApiController extends ApiController
{
  /**
   * @Route("/api/v1/serviceproviders/{hashId}", name="deleteServiceProvider", methods={"DELETE"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function deleteServiceProvider($hashId, ServiceProviderManager $serviceProviderManager)
  {
    //get service provider
    $serviceProvider = $serviceProviderManager->getServiceProvider($hashId);

    //check for valid service provider
    if (!$serviceProvider)
      return $this->respondWithErrors(['Invalid data']);

    //delete service provider
    $serviceProviderManager->deleteServiceProvider($serviceProvider);

    //respond with object
    return $this->respond($serviceProvider);
  }
}
