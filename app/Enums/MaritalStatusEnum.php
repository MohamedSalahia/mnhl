<?php

namespace App\Enums;

abstract class MaritalStatusEnum
{
    public const SINGLE = 'single';
    public const MARRIED = 'married';
    public const DIVORCED = 'divorced';
    public const WIDOWED = 'widowed';
    public const SEPARATED = 'separated';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
