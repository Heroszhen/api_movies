<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/test')]
class TestController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly HttpClientInterface $httpClient
    )
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

    #[Route('/unocess_rules', name: 'test_unocess_rules')]
    public function unocess_rules(): Response
    {
        $countries = [];
        try {
            $httpClient = HttpClient::create([
                'http_version' => '1.1',
                'timeout' => 60
            ]);
            
            $response = $httpClient->request('GET', "https://restcountries.com/v3.1/all?fields=languages,capital,area,maps,name,population,flags");
            if (200 === $response->getStatusCode()) {
                $countries = $response->toArray();
            }
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

        return $this->render('test/unocess_rules.html.twig', [
            'countries' => $countries
        ]);
    }
}
