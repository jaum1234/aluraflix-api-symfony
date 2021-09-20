<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;

interface IRelatedEntitiesCantBeDeleted
{
    public function setDefaultValueForRelatedEntities($entity);
}