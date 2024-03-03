<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/actrices', name: 'admin_actresses')]
    public function index(): Response
    {
        return $this->render('admin/admin/index.html.twig');
    }

    #[Route('/films', name: 'admin_movies')]
    public function getAllMovies(): Response
    {
        return $this->render('admin/admin/movies.html.twig');
    }
}
