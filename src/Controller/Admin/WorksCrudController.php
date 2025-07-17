<?php

namespace App\Controller\Admin;

use App\Entity\Works;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, IdField, TextEditorField, TextField, UrlField, ImageField};
use Vich\UploaderBundle\Form\Type\VichImageType;

class WorksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Works::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('company'),
            TextField::new('description'),
            UrlField::new('link'),
            TextField::new('projectType'),
            AssociationField::new('skills')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->setRequired(false)
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('multiple', true),
            ImageField::new('thumbnail_url')
                ->setBasePath('/images/uploads')
                ->onlyOnIndex(),
            TextField::new('thumbnailFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
        ];
    }
}
