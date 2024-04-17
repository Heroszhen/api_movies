<?php

namespace App\Controller\Api;

use App\Entity\Actress;
use App\Service\UtilService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateActressAction extends AbstractController
{
    public function __construct(private UtilService $utilService)
    {}

    public function __invoke(Request $request): Actress
    {
        $uploadedFile = $request->files->get('invoiceFile');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        
        $actress = new Actress();
        $actress->setInvoiceFile($uploadedFile);

        $post = $request->request;
        $actress
            ->setName($this->utilService->setNull($post->get('name')))
            ->setCountry($this->utilService->setNull($post->get('country')))
            ->setDescription($this->utilService->setNull($post->get('description')))
        ;
        if (!in_array($this->utilService->setNull($post->get('birthday')), [null, ''])) {
            $actress->setBirthday(new \DateTime($post->get('birthday')));
        }

        return $actress;
    }
}