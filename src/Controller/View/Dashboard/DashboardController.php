<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Model\AppConfig;

class DashboardController extends AbstractController
{
  #[Route('/dashboard', name: 'dashboardHome')]
  public function home()
  {
    return $this->render('dashboard/home.html.twig');
  }
}
