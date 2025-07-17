<?php

namespace App\Controller\Admin;

use App\Entity\SocialsNetworks;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextEditorField, TextField};

use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SocialsNetworksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialsNetworks::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('link'),
            ImageField::new('icon_url')
                ->setBasePath('/images/uploads')
                ->onlyOnIndex(),
            TextField::new('iconFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
        ];
    }
}
