<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Factory\AttributeFactory;

class AttributeApiController extends ApiController
{
  /**
   * @Route("/api/v1/attributes/{hashId}", name="deleteAttribute", methods={"DELETE"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
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
