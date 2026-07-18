<?php

namespace App\Enums;

abstract class AttendanceStatusEnum
{
    public const PRESENT = 'present';
    public const ABSENT = 'absent';
    public const LATE = 'late';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
