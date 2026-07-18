<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Models\Route;
use App\Models\TruckType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user) {

            if ($user->hasRole(UserTypeEnum::SUPER_ADMIN) || $user->hasRole(UserTypeEnum::ADMIN)) {

                return redirect()->route('admin.home');

            } else if (
                $user->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN) || $user->hasRole(UserTypeEnum::ORGANIZATION_ADMIN)
            ) {

                return redirect()->route('organization.home');

            } else if ($user->hasRole(UserTypeEnum::TEACHER)) {

                return redirect()->route('teacher.home');

            }

        }//end of if

        return redirect()->route('login');

    }

    public function save(Request $request)
    {
        $image = $request->image;
        /*
        |--------------------------------------------------------------------------
        | Remove Base64 Header
        |--------------------------------------------------------------------------
        */

        $image = str_replace(
            'data:image/png;base64,',
            '',
            $image
        );

        $image = str_replace(' ', '+', $image);

        /*
        |--------------------------------------------------------------------------
        | Decode
        |--------------------------------------------------------------------------
        */

        $imageData = base64_decode($image);

        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName = 'certificate_'.time().'.png';

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */
        Storage::disk('public')->put(

            'certificates/'.$fileName,

            $imageData
        );

        return response()->json([

            'success' => true,

            'url' => asset(
                'storage/certificates/'.$fileName
            )
        ]);
    }

}//end of controller
