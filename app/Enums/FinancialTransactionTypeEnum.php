<?php

namespace App\Enums;

enum FinancialTransactionTypeEnum: string
{
    case INCOME  = 'income';
    case EXPENSE = 'expense';
}
