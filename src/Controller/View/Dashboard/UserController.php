<?php

namespace App\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\Factory\UserFactory;

class UserController extends AbstractController
{
  #[Route('/dashboard/users', name: 'viewUsers')]
  public function viewall(UserFactory $userFactory)
  {
    $users = $userFactory->getUsers();

    return $this->render('dashboard/user/viewall.html.twig', [
      'users' => $users
    ]);
  }

  #[Route('/dashboard/users/add', name: 'addUser')]
  public function add(Request $req, UserFactory $userFactory)
  {
    // create user object
    $user = new User();

    // create form
    $form = $this->createForm(UserType::class, $user);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $userFactory->createUser($user);

      $this->addFlash('success', 'User added');
      return $this->redirectToRoute('viewUsers');
    }

    return $this->render('dashboard/user/add.html.twig', [
      'userForm' => $form->createView()
    ]);
  }

  #[Route('/dashboard/users/{hashId}', name: 'editUser')]
  public function edit($hashId, Request $req, UserFactory $userFactory)
  {
    // get user from database
    $user = $userFactory->getUser($hashId);

    // create form
    $form = $this->createForm(UserType::class, $user);

    // handle form request
    $form->handleRequest($req);

    // save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      // get new password field and update user
      $newPassword = $form->get('password')->getData();
      $userFactory->updateUser($user, $newPassword);

      $this->addFlash('success', 'User updated');
      return $this->redirectToRoute('viewUsers');
    }

    return $this->render('dashboard/user/edit.html.twig', [
      'userForm' => $form->createView()
    ]);
  }
}
