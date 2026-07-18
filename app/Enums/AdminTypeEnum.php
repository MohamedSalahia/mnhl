<?php

namespace App\Enums;

abstract class AdminTypeEnum
{
    public const SUPER_ADMIN = 'SuperAdmin';
    public const ADMIN = 'Admin';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class

