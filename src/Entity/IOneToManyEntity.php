<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;

interface IOneToManyEntity
{
    public function removeEntity($entity);
}