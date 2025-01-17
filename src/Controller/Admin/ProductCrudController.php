<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            //->setDateFormat('...')
            // ...
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom')->setHelp('Nom de votre produit'),
            SlugField::new('slug')->setTargetFieldName('name')->setLabel('URL')->setHelp('URL de votre produit generee automatiquement'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('Description de votre produit'),
            ImageField::new('Illustration')->setLabel('Image')->setUploadDir('/public/uploads')->setHelp('Image du votre produit 600x600')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads'),
            NumberField::new('price')->setLabel('Prix H.T.')->setHelp('Pris HT du produit sans sigle'),
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setChoices([
                '5,5' => '5,5',
                '10' => '10',
                '20' => '20',
            ]),
            AssociationField::new('category','Categorie associée'),// creation de fonction dans entité category __tostring
        ];
    }
    
}
