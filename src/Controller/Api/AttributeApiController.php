<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\Factory\AttributeFactory;

class AttributeApiController extends ApiController
{
  #[Route('/api/v1/attributes/{hashId}', name: 'deleteAttribute', methods: ['DELETE'])]
  #[IsGranted('ROLE_ADMIN')]
  public function deleteAttribute($hashId, AttributeFactory $attributeFactory)
  {
    // get attribute
    $attribute = $attributeFactory->getAttribute($hashId);

    // check for valid attribute
    if (!$attribute)
      return $this->respondWithErrors(['Invalid data']);

    // delete attribute
    $attributeFactory->deleteAttribute($attribute);

    return $this->respond($attribute);
  }
}
