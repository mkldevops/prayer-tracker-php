<?php

namespace App\Controller\Admin;

use App\Entity\Program;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProgramCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Program::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Program')
            ->setEntityLabelInPlural('Program')
            ->setSearchFields(['id', 'dayObjective', 'name']);
    }

    public function configureFields(string $pageName): iterable
    {
        $dayObjective = IntegerField::new('dayObjective');
        $name = TextField::new('name');
        $enable = Field::new('enable');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $objectives = AssociationField::new('objectives');
        $user = AssociationField::new('user');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $dayObjective, $name, $enable, $createdAt, $objectives, $user];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $dayObjective, $name, $enable, $createdAt, $updatedAt, $objectives, $user];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$dayObjective, $name, $enable, $createdAt, $updatedAt, $objectives, $user];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$dayObjective, $name, $enable, $createdAt, $updatedAt, $objectives, $user];
        }
    }
}
