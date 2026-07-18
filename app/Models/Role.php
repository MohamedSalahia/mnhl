<?php

namespace App\Models;

class Role extends \Laratrust\Models\Role
{
    protected $fillable = ['name', 'display_name', 'description', 'organization_id'];
}
