<?php

namespace App\Http\Requests\Organization;

use App\Enums\AssetTypeEnum;
use App\Rules\AssetResolution;
use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'organization_id' => 'required',
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,svg,webp,gif,doc,docx,pdf'],
            'type' => ['required', 'in:' . implode(',', AssetTypeEnum::getConstants())],
            'related_to' => 'sometimes|nullable',
        ];

        if ($this->hasFile('file')) {

            $type = $this->getAssetType();

            $maxSize = match ($type) {
                AssetTypeEnum::IMAGE => 20 * 1024, // 20 MB for images
                AssetTypeEnum::WORD => 20 * 1024, // 20 MB for Word documents
                AssetTypeEnum::PDF => 20 * 1024, // 20 MB for PDF documents
                default => 4096, // Default to 4 MB
            };

            $rules['file'][] = 'max:' . $maxSize;

        }// end of if

        return $rules;
    }

    public function prepareForValidation()
    {
        $user = auth('sanctum')->user() ?? auth()->user();

        return $this->merge([
            'user_id' => $user?->id,
            'organization_id' => session('selected_organization')['id'],
            'type' => $this->getAssetType(),
        ]);


    }// end of prepareForValidation

    public function attributes(): array
    {
        return [
            'file' => __('assets.file'),
        ];

    }

    protected function getAssetType()
    {
        $extension = strtolower($this->file('file')->getClientOriginalExtension());

        $wordExtensions = ['doc', 'docx'];

        $pdfExtensions = ['pdf'];

        $imageExtensions = ['jpg', 'jpeg', 'png', 'svg', 'webp', 'gif'];

        if (in_array($extension, $wordExtensions, true)) {

            return AssetTypeEnum::WORD;

        }

        if (in_array($extension, $pdfExtensions, true)) {

            return AssetTypeEnum::PDF;

        }

        if (in_array($extension, $imageExtensions, true)) {

            return AssetTypeEnum::IMAGE;

        }

        return null;

    }//end of getAssetType

}//end of request
