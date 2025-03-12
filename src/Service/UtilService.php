<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class UtilService
{
    public function setNull($data): mixed
    {
        if ('null' === $data) {
           return null;
        }


        return $data;
    }

    public function getErrorMessages(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}