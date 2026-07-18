<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappTemplateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type'        => 'required|string|max:100|unique:whatsapp_templates,type,' . $this->route('whatsapp_template')?->id,
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'is_active'   => 'nullable|boolean',
        ];
    }
}
