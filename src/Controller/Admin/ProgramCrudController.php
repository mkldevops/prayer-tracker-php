<?php

declare(strict_types=1);

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
            ->setSearchFields(['id', 'dayObjective', 'name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id', 'ID')->hideOnForm();

        yield IntegerField::new('dayObjective');

        yield TextField::new('name');

        yield Field::new('enable');

        yield AssociationField::new('objectives');

        yield AssociationField::new('user');

        yield DateTimeField::new('createdAt')->hideOnForm();

        yield DateTimeField::new('updatedAt')->hideOnForm();
    }
}
