<?php

namespace App\Enums;

enum TransactionType: string
{
    case DairySale     = 'dairy_sale';
    case DairyPurchase = 'dairy_purchase';
    case GeneralSale   = 'general_sale';
    case Construction  = 'construction_sale';

    public function label(): string
    {
        return match($this) {
            self::DairySale     => 'Dairy Sale',
            self::DairyPurchase => 'Dairy Purchase',
            self::GeneralSale   => 'General Sale',
            self::Construction  => 'Construction',
        };
    }
}
