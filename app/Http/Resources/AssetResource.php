<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'file' => $this->file_path,
            'thumbnail' => $this->preview_path,
            'type' => $this->type,
            'edit_url' => route('organization.assets.edit', $this->id),
            'delete_url' => route('organization.assets.destroy', $this->id),
        ];

        if (request()->wantsJson()) {

            unset($data['edit_url']);
            unset($data['delete_url']);

        }

        return $data;

    }//end of toArray

}//end of resource
