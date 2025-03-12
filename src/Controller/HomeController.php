<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MilleretRegistrationType;
use App\Service\UtilService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly UtilService $utilService
    )
    {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/milleret-inscription', name: 'app_milleret_registration')]
    public function milleret_registration(Request $request): Response
    {
        $questions = [
            [
                'id' => '67d1dbc5a6932',
                'code' => 'ab01',
                'question' => 'Qui est le plus grand héros du monde ?',
                'type' => 'text-image',
                'responses' => [
                    [
                        'id' => '67d1dc19e0103',
                        'text' => 'zhen1',
                        'image' => 'https://cdn-icons-png.freepik.com/256/3037/3037687.png'
                    ],
                    [
                        'id' => '67d1dcaf27eb3',
                        'text' => 'zhen2',
                        'image' => 'https://cdn-icons-png.flaticon.com/256/6645/6645779.png'
                    ],
                    [
                        'id' => '67d1dccfb61f3',
                        'text' => 'zhen2',
                        'image' => 'https://cdn-icons-png.flaticon.com/256/6645/6645703.png'
                    ]
                ]
            ],
            [
                'id' => '67d1ddfe2af8a',
                'code' => 'de09',
                'question' => 'Quel âge a zhen ?',
                'type' => 'nombre'
            ]
        ];

        $user = new User();
        $user
            ->setEmail("abc@gmail.com")
            ->setPassword("lllllllllllll")
        ;
        $form = $this->createForm(MilleretRegistrationType::class, $user);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            dd($request->request->all());
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    return $this->json([
                        
                    ], 201);
                } else {
                    $messages = $this->utilService->getErrorMessages($form);

                    return $this->json([
                        'data' => $messages
                    ], 400);
    
                }
            }
        }

        return $this->render('home/milleret_registration.html.twig', [
            'form' => $form->createView(),
            'questions' => $questions
        ]);
    }
}
