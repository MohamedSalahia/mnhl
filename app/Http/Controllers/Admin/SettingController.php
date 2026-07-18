<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsGeneralDataRequest;
use App\Models\Setting;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_settings')
        ];

    }

    public function generalData()
    {
        return view('admin.settings.general_data');

    }// end of index

    public function storeGeneralData(SettingsGeneralDataRequest $request)
    {
        $requestData = $request->validated();

        if ($request->logo) {
            Storage::disk('public')->delete('uploads/' . setting('logo'));
            $request->logo->store('uploads', 'public');
            $requestData['logo'] = $request->logo->hashName();
        }

        if ($request->fav_icon) {
            Storage::disk('public')->delete('uploads/' . setting('fav_icon'));
            $request->fav_icon->store('uploads', 'public');
            $requestData['fav_icon'] = $request->fav_icon->hashName();
        }

        setting($requestData)->save();

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.home'),
        ]);

    }// end of store

}//end of controller


