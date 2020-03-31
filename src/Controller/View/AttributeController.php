<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Attribute;
use App\Form\AttributeType;
use App\Service\Manager\AttributeManager;

class AttributeController extends AbstractController
{
  /**
   * @Route("/dashboard/attributes", name="viewAttributes")
   */
  public function view(AttributeManager $attrManager)
  {
    // get attributes
    $attributes = $attrManager->getAttributes();

    return $this->render('dashboard/attribute/viewall.html.twig', [
      'attributes' => $attributes
    ]);
  }

  /**
   * @Route("/dashboard/attributes/add", name="addAttribute")
   */
  public function add(Request $req, AttributeManager $attrManager)
  {
    $attribute = new Attribute();

    // create form object
    $form = $this->createForm(AttributeType::class, $attribute);

    // handle form request
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $attrManager->createAttribute($attribute);

      $this->addFlash('success', 'Attribute created');
      return $this->redirectToRoute('viewAttributes');
    }

    return $this->render('dashboard/attribute/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/attributes/{hashId}", name="editAttribute")
   */
  public function edit($hashId, Request $req, AttributeManager $attrManager)
  {
    // get attribute
    $attribute = $attrManager->getAttribute($hashId);

    // create form object
    $form = $this->createForm(AttributeType::class, $attribute);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $attrManager->updateAttribute();

      $this->addFlash('success', 'Attribute updated');
      return $this->redirectToRoute('viewAttributes');
    }

    return $this->render('dashboard/attribute/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
