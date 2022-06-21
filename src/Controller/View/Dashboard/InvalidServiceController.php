<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Factory\InvalidServiceFactory;

class InvalidServiceController extends AbstractController
{
  #[Route('/dashboard/invalidservices', name: 'viewInvalidServices')]
  public function viewAll(InvalidServiceFactory $invalidServiceFactory)
  {
    // get invalid services
    $invalidServices = $invalidServiceFactory->getInvalidServices();

    return $this->render('dashboard/invalidservice/viewall.html.twig', [
      'invalidServices' => $invalidServices
    ]);
  }
}
