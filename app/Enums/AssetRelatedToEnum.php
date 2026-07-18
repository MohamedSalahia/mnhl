<?php

namespace App\Enums;

abstract class AssetRelatedToEnum
{
    public const TEACHER_CERTIFICATE = 'teacher_certificate';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class

