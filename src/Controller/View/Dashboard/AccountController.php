<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserType;
use App\Service\Factory\UserFactory;

class AccountController extends AbstractController
{
  #[Route('/dashboard/account', name: 'viewAccount')]
  public function view(Request $req, UserFactory $userFactory)
  {
    $user = $this->getUser();

    // create form
    $form = $this->createForm(UserType::class, $user);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      // get new password field and update user
      $newPassword = $form->get('password')->getData();
      $userFactory->updateUser($user, $newPassword);

      $this->addFlash('success', 'Account updated');
    }

    return $this->render('dashboard/account/view.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
