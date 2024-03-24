<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\PrayerName;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PrayerNameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PrayerName::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('PrayerName')
            ->setEntityLabelInPlural('PrayerName')
            ->setSearchFields(['id', 'name', 'description'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id', 'ID')->hideOnForm();

        yield TextField::new('name');

        yield TextField::new('description');

        yield Field::new('enable');

        yield DateTimeField::new('createdAt')->hideOnForm();

        yield DateTimeField::new('updatedAt')->hideOnForm();
    }
}
