<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Prayer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class PrayerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prayer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Prayer')
            ->setEntityLabelInPlural('Prayer')
            ->setSearchFields(['id'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id', 'ID')->hideOnForm();

        yield AssociationField::new('prayerName');

        yield AssociationField::new('objective');

        yield AssociationField::new('user');

        yield DateTimeField::new('accomplishedAt')->hideOnForm();

        yield DateTimeField::new('createdAt')->hideOnForm();

        yield DateTimeField::new('updatedAt')->hideOnForm();
    }
}
