<?php

namespace App\Controller\View\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
  #[Route('/')]
  public function siteRoot()
  {
    return $this->redirect($_ENV['APP_ESCAPE_URL']);
  }
}
