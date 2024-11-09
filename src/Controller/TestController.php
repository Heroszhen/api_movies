<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/password', name: 'app_password')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/users', name: 'test_users_jquery')]
    public function beautiful_girls(): Response
    {
        return $this->render('test/users.html.twig');
    }

    #[Route('/chartjs', name: 'test_chartjs')]
    public function chartjs(): Response
    {
        return $this->render('test/chartjs.html.twig');
    }

    #[Route('/users-petitevue', name: 'test_users_petitvue')]
    public function petitevue(): Response
    {
        return $this->render('test/petitevue.html.twig');
    }

    #[Route('/users-react', name: 'test_users_react')]
    public function react(): Response
    {
        return $this->render('test/users_react.html.twig');
    }
}
