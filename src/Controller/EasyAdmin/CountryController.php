<?php

namespace App\Controller\EasyAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryController extends AbstractController
{
    #[Route('/easy-admin/pays', name: 'easy_admin_countries')]
    public function index(Request $request, HttpClientInterface $httpClient): Response
    {
        $response = $httpClient->request('GET', 'https://restcountries.com/v3.1/all');
        $countries = $response->toArray();
        
        return $this->render('bundles/EasyAdminBundle/page/country.html.twig', [
           'countries' => $countries
        ]);
    }

}
