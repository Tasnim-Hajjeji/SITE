<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class document extends Model
{
     protected $table = 'document';

    protected $fillable = [
        'name_fr',
        'name_en',
        'description_fr',
        'description_en',
        'url',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
