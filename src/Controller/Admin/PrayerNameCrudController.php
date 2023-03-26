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
        $name = TextField::new('name');
        $description = TextField::new('description');
        $enable = Field::new('enable');
        $id = IntegerField::new('id', 'ID');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $description, $enable, $createdAt];
        }
        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $description, $enable, $createdAt, $updatedAt];
        }
        if (Crud::PAGE_NEW === $pageName) {
            return [$name, $description, $enable];
        }
        if (Crud::PAGE_EDIT === $pageName) {
            return [$name, $description, $enable];
        }
    }
}
