<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Model\AppConfig;
use App\Form\SettingType;

class SettingsController extends AbstractController
{
  /**
   * @Route("/dashboard/settings")
   */
  public function update(Request $request, AppConfig $appConfig)
  {
    $form = $this->createForm(SettingType::class, $appConfig);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $appConfig->save();
      $this->addFlash('success', 'App settings updated');
    }

    return $this->render('dashboard/settings/viewall.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
