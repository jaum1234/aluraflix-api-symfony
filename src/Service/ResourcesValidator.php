<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResourcesValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($entity)
    {
        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {
            return [
                'success' => false,
                'errors' => $errors,
            ]; 
        }

        return [
            'success' => true
        ];
    }
}