<?php

namespace App\Enums;

abstract class GenderEnum
{
    public const MALE = 'male';
    public const FEMALE = 'female';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
