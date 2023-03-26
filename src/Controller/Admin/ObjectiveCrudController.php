<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Objective;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ObjectiveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objective::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Objective')
            ->setEntityLabelInPlural('Objective')
            ->setSearchFields(['id', 'number'])
        ;
    }

    /**
     * @return array<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id', 'ID')->hideOnForm();
        yield AssociationField::new('program');
        yield AssociationField::new('prayerName');
        yield IntegerField::new('number');
        yield Field::new('enable');
        yield AssociationField::new('prayers');
        yield TextareaField::new('user');
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('updatedAt')->hideOnForm();
    }
}
