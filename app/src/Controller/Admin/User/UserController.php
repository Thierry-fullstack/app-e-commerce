<?php

namespace App\Controller\Admin\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/admin/user/user', name: 'app_admin_user_user')]
    public function index(): Response
    {
        return $this->render('admin/user/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
