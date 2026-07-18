<?php

namespace App\Models;

use App\Enums\AssetTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asset extends Model
{
    protected $fillable = [
        'organization_id', 'teacher_id', 'type', 'file', 'order', 'related_to'
    ];

    //attr
    public function getFilePathAttribute()
    {
        return Storage::disk('public')->url('uploads/' . $this->file);

    }// end of getFilePathAttribute

    public function getPreviewPathAttribute()
    {
        return match ($this->type) {
            AssetTypeEnum::IMAGE => Storage::disk('public')->url('uploads/' . $this->file),
            AssetTypeEnum::WORD => asset('web_assets/images/word.png'),
            AssetTypeEnum::PDF => asset('web_assets/images/pdf.png'),
            default => asset('web_assets/images/default.png'),
        };

    }// end of getPreviewPathAttribute

    //scope
    public function scopeWhenNurseryId($query, $nurseryId)
    {
        return $query->when($nurseryId, function ($q) use ($nurseryId) {

            $nurseryId = Nursery::keyFromHashId($nurseryId);

            return $q->where('nursery_id', $nurseryId);

        });

    }// end of scopeWhenNurseryId

    public function scopeWhenRelatedTo($query, $relatedTo)
    {
        return $query->when($relatedTo, function ($q) use ($relatedTo) {

            return $q->where('related_to', $relatedTo);

        });

    }// end of scopeWhenRelatedTo

    public function scopeWhenTeacherId($query, $teacherId)
    {
        return $query->when($teacherId, function ($q) use ($teacherId) {

            return $q->where('teacher_id', $teacherId);

        });

    }// end of scopeWhenTeacherId

    public function scopeWhenDate($query, $date)
    {
        return $query->when($date, function ($q) use ($date) {

            return $q->join('student_attached_asset', 'assets.id', '=', 'student_attached_asset.asset_id')
                ->whereDate('student_attached_asset.created_at', $date)
                ->distinct()
                ->select('assets.*');

        });

    }// end of scopeWhenDate

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    //fun

}//end of model
