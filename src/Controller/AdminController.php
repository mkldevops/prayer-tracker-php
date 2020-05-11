<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    public function persistEntity($entity)
    {
        if (method_exists($entity, 'setUser') && $entity->getUser() === null) {
            $entity->setUser($this->getUser());
        }

        parent::persistEntity($entity);
    }
}
