<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Governorate;

class GovernorateController extends Controller
{
    public function areas(Governorate $governorate)
    {
        $areas = $governorate->areas;

        $emptyValueText = request()->empty_value_text;

        return view('organization.governorates._areas', compact('emptyValueText', 'areas'));

    }// end of areas

}//end of controller
