<?php

namespace App\Enums;

abstract class ClassroomTypeEnum
{
    public const INDIVIDUAL = 'individual';
    public const GROUP = 'group';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
