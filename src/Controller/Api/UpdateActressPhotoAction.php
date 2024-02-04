<?php

namespace App\Controller\Api;

use App\Entity\Actress;
use App\Repository\ActressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class UpdateActressPhotoAction extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    public function __invoke(Request $request, int $id): Actress
    {
        $uploadedFile = $request->files->get('invoiceFile');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $actress = $this->entityManager->find(Actress::class, $id);
        $actress->setInvoiceFile($uploadedFile);

        return $actress;
    }
}