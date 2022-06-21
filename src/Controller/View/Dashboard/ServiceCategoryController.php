<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;
use App\Service\Factory\ServiceCategoryFactory;

class ServiceCategoryController extends AbstractController
{
  #[Route('/dashboard/servicecategories', name: 'viewServiceCategories')]
  public function view(ServiceCategoryFactory $serviceCategoryFactory)
  {
    // get service categories
    $serviceCategories = $serviceCategoryFactory->getServiceCategories();

    return $this->render('dashboard/servicecategory/viewall.html.twig', [
      'serviceProviderCategories' => $serviceCategories
    ]);
  }

  #[Route('/dashboard/servicecategories/add', name: 'addServiceCategory')]
  public function add(Request $req, ServiceCategoryFactory $serviceCategoryFactory)
  {
    // handle form request
    $serviceCategory = new ServiceCategory();
    $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
      $serviceCategoryFactory->createServiceCategory($serviceCategory);

      $this->addFlash('success', 'Service Category created');
      return $this->redirectToRoute('viewServiceCategories');
    }

    return $this->render('dashboard/servicecategory/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/servicecategories/{hashId}', name: 'editServiceCategory')]
  public function edit($hashId, Request $req, ServiceCategoryFactory $serviceCategoryFactory)
  {
    // handle form request
    $serviceCategory = $serviceCategoryFactory->getServiceCategory($hashId);
    $form = $this->createForm(ServiceCategoryType::class, $serviceCategory);
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $serviceCategoryFactory->updateServiceCategory();

      $this->addFlash('success', 'Service Category updated');
      return $this->redirectToRoute('viewServiceCategories');
    }

    return $this->render('dashboard/servicecategory/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
