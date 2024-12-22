<?php

namespace App\Controller\Admin;

use App\Entity\DataPrototype\Man;
use App\Form\ManType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/data-prototype')]
class DataPrototypeController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/mecs', name: 'admin_data_prototype_men')]
    public function index(Request $request): Response
    {
        $men = $this->entityManager->getRepository(Man::class)->findAll();dump($men);

        return $this->render('admin/data_prototype/index.html.twig', [
           'men' => $men
        ]);
    }

    #[Route('/editer-mec/{id?}', name: 'admin_data_prototype_edit_man', methods: ['GET', 'POST'])]
    public function editMan(Request $request, ?int $id = null): Response
    {
        if ($id === null) {
            $man = new Man();
        } else {
            $man = $this->entityManager->find(Man::class, $id);
        }

        $form = $this->createForm(ManType::class, $man);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($man);
            $this->entityManager->flush();

            if ($id === null) {
                return $this->redirectToRoute('admin_data_prototype_edit_man', ['id' => $man->getId()]);
            }
        }

        return $this->render('admin/data_prototype/edit_man.html.twig', [
            'form' => $form->createView(),
            'man' => $man
        ]);
    }
}
