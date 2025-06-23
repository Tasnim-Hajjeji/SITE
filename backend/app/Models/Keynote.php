<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keynote extends Model
{
    protected $fillable = [
        'description_fr',
        'description_en',
        'speaker_name',
        'speaker_role',
        'image_url',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
