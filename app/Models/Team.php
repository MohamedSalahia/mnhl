<?php

namespace App\Models;

use Laratrust\Models\Team as TeamModel;

class Team extends TeamModel
{
    protected $fillable = ['name', 'display_name', 'description'];


}// end of model
