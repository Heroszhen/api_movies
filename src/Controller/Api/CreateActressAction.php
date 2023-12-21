<?php

namespace App\Controller\Api;

use App\Entity\Actress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateActressAction extends AbstractController
{
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
            ->setName($post->get('name'))
            ->setCountry($post->get('country'))
            ->setDescription($post->get('description'))
        ;
        if (!in_array($post->get('birthday'), [null, ''])) {
            $actress->setBirthday(new \DateTime($post->get('birthday')));
        }

        return $actress;
    }
}