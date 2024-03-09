<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Householder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the resident that owns the Householder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
