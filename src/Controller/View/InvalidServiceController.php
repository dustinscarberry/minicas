<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\InvalidService;

class InvalidServiceController extends AbstractController
{
  /**
   * @Route("/dashboard/invalidservices", name="viewInvalidServices")
   */
  public function viewAll()
  {
    $invalidServices = $this->getDoctrine()
      ->getRepository(InvalidService::class)
      ->findAll();

    return $this->render('dashboard/invalidservice/viewall.html.twig', [
      'invalidServices' => $invalidServices
    ]);
  }
}
