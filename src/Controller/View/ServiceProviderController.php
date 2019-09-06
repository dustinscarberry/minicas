<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\ServiceProvider;
use App\Form\ServiceProviderType;

class ServiceProviderController extends AbstractController
{
  /**
   * @Route("/dashboard/serviceproviders", name="viewServiceProviders")
   */
  public function view()
  {
    $serviceProviders = $this->getDoctrine()
      ->getRepository(ServiceProvider::class)
      ->findAll();

    //render page
    return $this->render('dashboard/serviceprovider/viewall.html.twig', [
      'serviceProviders' => $serviceProviders
    ]);
  }

  /**
   * @Route("/dashboard/serviceproviders/add", name="addServiceProvider")
   */
  public function add(Request $req)
  {
    $serviceProvider = new ServiceProvider();

    //create form object
    $form = $this->createForm(ServiceProviderType::class, $serviceProvider);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $em = $this->getDoctrine()->getManager();

      $em->persist($serviceProvider);
      $em->flush();

      $this->addFlash('success', 'Service Provider created');
      return $this->redirectToRoute('viewServiceProviders');
    }

    //render page
    return $this->render('dashboard/serviceprovider/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/serviceproviders/{hashId}", name="editServiceProvider")
   */
  public function edit($hashId, Request $req)
  {
    $serviceProvider = $this->getDoctrine()
      ->getRepository(ServiceProvider::class)
      ->findByHashId($hashId);

    //get original attributes to compare against
    $originalAttributes = new ArrayCollection();
    foreach ($serviceProvider->getAttributeMappings() as $mapping)
      $originalAttributes->add($mapping);

    //create form object
    $form = $this->createForm(ServiceProviderType::class, $serviceProvider);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      //remove deleted services from database
      foreach ($originalAttributes as $attribute)
      {
        if ($serviceProvider->getAttributeMappings()->contains($attribute) === false)
          $this->getDoctrine()->getManager()->remove($attribute);
      }

      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success', 'Service Provider updated');
      return $this->redirectToRoute('viewServiceProviders');
    }

    //render page
    return $this->render('dashboard/serviceprovider/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
