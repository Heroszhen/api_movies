<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class UserController extends AbstractController
{
    #[Route('/utilisateurs', name: 'admin_users')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig');
    }
}
