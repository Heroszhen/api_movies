<?php

namespace App\Service;

class UtilService
{
    public function setNull($data): mixed
    {
        if ('null' === $data) {
           return null;
        }


        return $data;
    }
}