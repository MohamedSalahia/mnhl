<?php

namespace App\Enums;

abstract class AssetTypeEnum
{
    public const IMAGE = 'image';
    public const WORD = 'video';
    public const PDF = 'pdf';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class

