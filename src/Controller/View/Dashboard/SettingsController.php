<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Model\AppConfig;
use App\Form\SettingType;

class SettingsController extends AbstractController
{
  #[Route('/dashboard/settings')]
  public function update(Request $request, AppConfig $appConfig)
  {
    // create form
    $form = $this->createForm(SettingType::class, $appConfig);

    // handle form request
    $form->handleRequest($request);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      // save app config
      $appConfig->save();
      $this->addFlash('success', 'App settings updated');
    }

    return $this->render('dashboard/settings/viewall.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
