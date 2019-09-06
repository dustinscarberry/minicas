<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\IdentityProvider;
use App\Form\IdentityProviderType;

class IdentityProviderController extends AbstractController
{
  /**
   * @Route("/dashboard/identityproviders", name="viewIdentityProviders")
   */
  public function view()
  {
    $idps = $this->getDoctrine()
      ->getRepository(IdentityProvider::class)
      ->findAll();

    //render page
    return $this->render('dashboard/identityprovider/viewall.html.twig', [
      'identityProviders' => $idps
    ]);
  }

  /**
   * @Route("/dashboard/identityproviders/add")
   */
  public function add(Request $req)
  {
    $identityProvider = new IdentityProvider();

    //create form object
    $form = $this->createForm(IdentityProviderType::class, $identityProvider);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $em = $this->getDoctrine()->getManager();

      $em->persist($identityProvider);
      $em->flush();

      $this->addFlash('success', 'Identity Provider created');
      return $this->redirectToRoute('viewIdentityProviders');
    }

    //render page
    return $this->render('dashboard/identityprovider/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/identityproviders/{hashId}", name="editIdentityProvider")
   */
  public function edit($hashId, Request $req)
  {
    $identityProvider = $this->getDoctrine()
      ->getRepository(IdentityProvider::class)
      ->findByHashId($hashId);

    //create form object
    $form = $this->createForm(IdentityProviderType::class, $identityProvider);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success', 'Identity Provider updated');
      return $this->redirectToRoute('viewIdentityProviders');
    }

    //render page
    return $this->render('dashboard/identityprovider/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
