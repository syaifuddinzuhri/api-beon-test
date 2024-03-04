<?php

namespace App\Models;

use App\Constant\UploadPathConstant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function setIdCardPhotoAttribute($value)
    {
        if ($value != null) {
            $this->attributes['id_card_photo'] = UploadPathConstant::ID_CARD_PHOTOS . $value;
        }
    }

    public function getIdCardPhotoAttribute()
    {
        return $this->attributes['id_card_photo'] ?  URL::to('/') . '/' . $this->attributes['id_card_photo'] : null;
    }
}
