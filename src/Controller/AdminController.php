<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    public function persistEntity($entity): void
    {
        if (method_exists($entity, 'setUser') && null === $entity->getUser()) {
            $entity->setUser($this->getUser());
        }
    }
}
