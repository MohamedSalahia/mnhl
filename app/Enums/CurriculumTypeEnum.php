<?php

namespace App\Enums;

abstract class CurriculumTypeEnum
{
    public const MAIN = 'main';
    public const ADDITIONAL = 'additional';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class

