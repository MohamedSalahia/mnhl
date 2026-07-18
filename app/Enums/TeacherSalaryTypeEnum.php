<?php

namespace App\Enums;

enum TeacherSalaryTypeEnum: string
{
    case PAYMENT   = 'payment';
    case BONUS     = 'bonus';
    case DEDUCTION = 'deduction';
    case ADVANCE   = 'advance';
}
