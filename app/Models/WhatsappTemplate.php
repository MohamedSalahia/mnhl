<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $fillable = [
        'type', 'title', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    //scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);

    }// end of scopeActive

    public function buildMessage(array $variables = []): string
    {
        $message = $this->description;

        foreach ($variables as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;

    }// end of buildMessage

}// end of model
