<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\Factory\ServiceCategoryFactory;

class ServiceCategoryApiController extends ApiController
{
  #[Route('/api/v1/servicecategories/{hashId}', name: 'deleteServiceCategory', methods: ['DELETE'])]
  #[IsGranted('ROLE_ADMIN')]
  public function deleteServiceCategory($hashId, ServiceCategoryFactory $serviceCategoryFactory)
  {
    // get service category
    $serviceCategory = $serviceCategoryFactory->getServiceCategory($hashId);

    // check for valid service category
    if (!$serviceCategory)
      return $this->respondWithErrors(['Invalid data']);

    // delete service category
    $serviceCategoryFactory->deleteServiceCategory($serviceCategory);

    return $this->respond($serviceCategory);
  }
}
