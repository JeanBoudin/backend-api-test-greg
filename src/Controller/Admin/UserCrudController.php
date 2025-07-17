<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{ArrayField, ChoiceField, EmailField, IdField, IntegerField, TelephoneField, TextEditorField, TextField};

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('name'),
            TextField::new('lastname'),
            TextEditorField::new('presentation'),
            TelephoneField::new('phone'),
            TextField::new('address'),
            TextField::new('city'),
            IntegerField::new('zipcode'),
            ChoiceField::new('roles')
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    // Ajoutez d'autres rôles selon vos besoins
                ])
                ->allowMultipleChoices(true)
        ];
    }
}
