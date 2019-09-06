<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Manager\AttributeManager;

class AttributeApiController extends ApiController
{
  /**
   * @Route("/api/v1/attributes/{hashId}", name="deleteAttribute", methods={"DELETE"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function deleteAttribute($hashId, AttributeManager $attributeManager)
  {
    //get attribute
    $attribute = $attributeManager->getAttribute($hashId);

    //check for valid attribute
    if (!$attribute)
      return $this->respondWithErrors(['Invalid data']);

    //delete attribute
    $attributeManager->deleteAttribute($attribute);

    //respond with object
    return $this->respond($attribute);
  }
}
