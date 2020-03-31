<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserType;
use App\Service\Manager\UserManager;

class AccountController extends AbstractController
{
  /**
   * @Route("/dashboard/account", name="viewAccount")
   */
  public function view(Request $req, UserManager $userManager)
  {
    $user = $this->getUser();

    // create form
    $form = $this->createForm(UserType::class, $user);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      // get new password field and update user
      $newPassword = $form->get('password')->getData();
      $userManager->updateUser($user, $newPassword);

      $this->addFlash('success', 'Account updated');
    }

    return $this->render('dashboard/account/view.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
