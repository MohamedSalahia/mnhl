<?php

namespace App\Enums;

abstract class AssessmentStatusEnum
{
    public const PENDING = 'pending';
    public const IN_PROGRESS = 'in_progress';
    public const PARTIALLY_IN_PROGRESS = 'partially_in_progress';
    public const COMPLETED = 'completed';

    public static function getConstants()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();

    }//end of getConstants

}//end of enum class
