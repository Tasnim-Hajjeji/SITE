<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keynote extends Model
{
    protected $fillable = [
        'description_fr',
        'description_en',
        'speakerable_id',
        'speakerable_type',
        'edition_id',
    ];

    public function speakerable()
    {
        return $this->morphTo();
    }
    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
