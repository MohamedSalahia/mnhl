<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    public function governorates(Country $country)
    {
        $governorates = $country->governorates;

        $emptyValueText = request()->empty_value_text;

        return view('organization.countries._governorates', compact('emptyValueText', 'governorates'));

    }// end of governorates

}//end of controller
