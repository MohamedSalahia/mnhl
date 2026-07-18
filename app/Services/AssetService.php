<?php

namespace App\Services;


use App\Models\Asset;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class AssetService
{
    public function storeAsset($request)
    {
        $requestData = $request->validated();

        if ($request->file('file')) {
            $requestData['file'] = $request->file('file')->hashName();
            $request->file('file')->store('uploads', 'public');
        }

        $asset = Asset::create($requestData);

        return $asset;

    }// end of storeAsset

}//end of service
