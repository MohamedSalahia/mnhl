<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\AssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Services\AssetService;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function store(AssetRequest $request, AssetService $assetService)
    {
        $asset = $assetService->storeAsset($request);

        return new AssetResource($asset);

    }// end of store

    public function reorder()
    {
        foreach (request()->ids as $index => $id) {

            Asset::where('id', $id)->first()->update(['order' => $index + 1]);

        }//end of for each

        return response()->json([
            'message' => __('site.updated_successfully')
        ]);

    }// end of reOrder

    public function destroy(Asset $asset)
    {
        Storage::disk('public')->delete('uploads/' . $asset->file);

        $asset->delete();

    }// end of destroy

}//end of controller
