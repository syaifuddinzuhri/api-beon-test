<?php

namespace App\Models;

use App\Constant\UploadPathConstant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class House extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'active_householder'
    ];

    public function getActiveHouseholderAttribute()
    {
        try {
            $data = Householder::with(['resident'])->where('house_id', $this->attributes['id'])->where('is_done', 0)->latest()->first();
            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
