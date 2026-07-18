<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('organization.profile.edit');

    }// end of getChangeData

    public function update(ProfileRequest $request)
    {
        $requestData = $request->validated();

        if ($request->image) {
            $requestData['image'] = $request->image->hashName();
            $request->image->store('uploads', 'public');
        }

        auth()->user()->update($requestData);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.home')
        ]);

    }// end of postChangeData

    public function switchLanguage(Request $request)
    {
        request()->validate([
            'locale' => 'required|in:' . implode(',', array_keys(config('localization.supportedLocales'))),
        ]);

        auth()->user()->update(['locale' => $request['locale']]);

        session(['locale' => $request['locale']]);

        return redirect()->back();

    }// end of switchLanguage

    public function leaveImpersonation()
    {
        auth()->user()->leaveImpersonation();

        return redirect()->route('admin.home');

    }// end of leaveImpersonation

}//end of controller
