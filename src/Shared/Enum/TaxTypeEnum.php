<?php

namespace App\Shared\Enum;

enum TaxTypeEnum: string
{
    case VAT = 'VAT';
    case GST_HST = 'GST/HST';
    case PST = 'PST';
}
