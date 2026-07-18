<?php

namespace App\Enums;

abstract class UserTypeEnum
{
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';

    public const ORGANIZATION_SUPER_ADMIN = 'organization_super_admin';
    public const ORGANIZATION_ADMIN = 'organization_admin';

    public const TEACHER = 'teacher';
    public const STUDENT = 'student';
    public const EXAMINER = 'examiner';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
