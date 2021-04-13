<?php

namespace App\Controller\Admin;

use App\Entity\Objective;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
            ->setSearchFields(['id', 'number']);
    }

    public function configureFields(string $pageName): iterable
    {
        $program = AssociationField::new('program');
        $prayerName = AssociationField::new('prayerName');
        $number = IntegerField::new('number');
        $enable = Field::new('enable');
        $id = IntegerField::new('id', 'ID');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $prayers = AssociationField::new('prayers');
        $user = TextareaField::new('user');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $user, $program, $prayerName, $number, $enable, $createdAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $number, $enable, $createdAt, $updatedAt, $program, $prayerName, $prayers];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$program, $prayerName, $number, $enable];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$program, $prayerName, $number, $enable];
        }
    }
}
