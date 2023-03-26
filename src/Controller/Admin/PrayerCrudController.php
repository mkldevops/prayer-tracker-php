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
        $accomplishedAt = DateTimeField::new('accomplishedAt');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $prayerName = AssociationField::new('prayerName');
        $objective = AssociationField::new('objective');
        $user = AssociationField::new('user');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $accomplishedAt, $createdAt, $prayerName, $objective, $user];
        }
        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $accomplishedAt, $createdAt, $updatedAt, $prayerName, $objective, $user];
        }
        if (Crud::PAGE_NEW === $pageName) {
            return [$accomplishedAt, $createdAt, $updatedAt, $prayerName, $objective, $user];
        }
        if (Crud::PAGE_EDIT === $pageName) {
            return [$accomplishedAt, $createdAt, $updatedAt, $prayerName, $objective, $user];
        }
    }
}
