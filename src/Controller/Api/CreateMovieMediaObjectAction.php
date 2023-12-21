<?php

namespace App\Controller\Api;

use App\Entity\MovieMediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateMovieMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request): MovieMediaObject
    {
        $uploadedFile = $request->files->get('invoiceFile');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        
        $object = new MovieMediaObject();
        $object->setInvoiceFile($uploadedFile);

        return $object;
    }
}