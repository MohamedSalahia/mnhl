<?php

namespace App\Enums;

enum TeacherSalaryCalculationTypeEnum: string
{
    case HOURLY = 'hourly';
    case FIXED  = 'fixed';
}
