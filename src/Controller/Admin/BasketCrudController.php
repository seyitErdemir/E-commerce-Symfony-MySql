<?php

namespace App\Controller\Admin;

use App\Entity\Basket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BasketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Basket::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),
            TextEditorField::new('address', 'Address'),
            CollectionField::new('product_id'),
            TextField::new('total_price'),
            BooleanField::new('checkMoney', "Onay Durumu"),
            BooleanField::new('status', "Müşteri Siparişi Aldımı"),

        ];
    }
}
