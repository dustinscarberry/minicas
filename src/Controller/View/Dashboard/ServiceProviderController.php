<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\ServiceProvider;
use App\Form\ServiceProviderType;
use App\Service\Factory\ServiceProviderFactory;

class ServiceProviderController extends AbstractController
{
  #[Route('/dashboard/serviceproviders', name: 'viewServiceProviders')]
  public function view()
  {
    // get service providers
    $serviceProviders = $this->getDoctrine()
      ->getRepository(ServiceProvider::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/serviceprovider/viewall.html.twig', [
      'serviceProviders' => $serviceProviders
    ]);
  }

  #[Route('/dashboard/serviceproviders/add', name: 'addServiceProvider')]
  public function add(Request $req, ServiceProviderFactory $spManager)
  {
    // create service provider object
    $serviceProvider = new ServiceProvider();

    // create form
    $form = $this->createForm(ServiceProviderType::class, $serviceProvider);

    // handle form reques
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      // create service provider
      $spManager->createServiceProvider($serviceProvider);

      $this->addFlash('success', 'Service Provider created');
      return $this->redirectToRoute('viewServiceProviders');
    }

    return $this->render('dashboard/serviceprovider/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/serviceproviders/{hashId}', name: 'editServiceProvider')]
  public function edit($hashId, Request $req, ServiceProviderFactory $spManager)
  {
    // get service provider
    $serviceProvider = $this->getDoctrine()
      ->getRepository(ServiceProvider::class)
      ->findByHashId($hashId);

    // get original attributes to compare against
    $originalAttributes = new ArrayCollection();
    foreach ($serviceProvider->getAttributeMappings() as $mapping)
      $originalAttributes->add($mapping);

    // create form
    $form = $this->createForm(ServiceProviderType::class, $serviceProvider);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      // update service provider
      $spManager->updateServiceProvider($serviceProvider, $originalAttributes);

      $this->addFlash('success', 'Service Provider updated');
      return $this->redirectToRoute('viewServiceProviders');
    }

    return $this->render('dashboard/serviceprovider/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
