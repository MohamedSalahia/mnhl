<?php

namespace App\Http\Controllers\Teacher\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\PasswordRequest;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('teacher.profile.password.edit');

    }// end of edit

    public function update(PasswordRequest $request)
    {
        $request->merge(['password' => bcrypt($request->password)]);

        auth()->user()->update($request->all());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('teacher.home')
        ]);

    }// end of update

}//end of controller
