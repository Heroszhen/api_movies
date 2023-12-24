<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'admin_home')]
    public function index(): Response
    {
        return $this->render('admin/home/index.html.twig', [
           
        ]);
    }

    #[Route('/get-token', name: 'admin_get_token', methods: 'GET')]
    public function getToken(JWTTokenManagerInterface $JWTManager): JsonResponse
    {   
        $response = [
            "status" => 1,
            "data" => $JWTManager->create($this->getUser())
        ];

        return $this->json($response);
    }
}
