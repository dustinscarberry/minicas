<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\IdentityProvider;
use App\Form\IdentityProviderType;
use App\Service\Manager\IdentityProviderManager;

class IdentityProviderController extends AbstractController
{
  /**
   * @Route("/dashboard/identityproviders", name="viewIdentityProviders")
   */
  public function viewAll()
  {
    // get identity providers
    $identityProviders = $this->getDoctrine()
      ->getRepository(IdentityProvider::class)
      ->findAll();

    return $this->render('dashboard/identityprovider/viewall.html.twig', [
      'identityProviders' => $identityProviders
    ]);
  }

  /**
   * @Route("/dashboard/identityproviders/add")
   */
  public function add(Request $req, IdentityProviderManager $idpManager)
  {
    // create identity provider object
    $identityProvider = new IdentityProvider();

    // create form
    $form = $this->createForm(IdentityProviderType::class, $identityProvider);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      // create identity provider
      $idpManager->createIdentityProvider($identityProvider);

      $this->addFlash('success', 'Identity Provider created');
      return $this->redirectToRoute('viewIdentityProviders');
    }

    return $this->render('dashboard/identityprovider/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/identityproviders/{hashId}", name="editIdentityProvider")
   */
  public function edit($hashId, Request $req, IdentityProviderManager $idpManager)
  {
    // get identity provider
    $identityProvider = $this->getDoctrine()
      ->getRepository(IdentityProvider::class)
      ->findByHashId($hashId);

    // create form
    $form = $this->createForm(IdentityProviderType::class, $identityProvider);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      // update identity provider
      $idpManager->updateIdentityProvider();

      $this->addFlash('success', 'Identity Provider updated');
      return $this->redirectToRoute('viewIdentityProviders');
    }

    return $this->render('dashboard/identityprovider/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
