<?php

namespace App\Enums;

abstract class OrganizationTeacherStatusEnum
{
    public const PENDING = 'pending';
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
