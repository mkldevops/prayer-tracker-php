<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('User')
            ->setSearchFields(['id', 'username', 'roles'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $username = TextField::new('username');
        $roles = TextField::new('roles');
        $enable = Field::new('enable');
        $id = IntegerField::new('id', 'ID');
        $password = TextField::new('password');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $programs = AssociationField::new('programs');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $username, $enable, $createdAt, $programs];
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $username, $roles, $password, $enable, $createdAt, $updatedAt, $programs];
        }

        if (Crud::PAGE_NEW === $pageName) {
            return [$username, $roles, $enable];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [$username, $roles, $enable];
        }
    }
}
