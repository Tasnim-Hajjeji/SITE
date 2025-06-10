<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class document extends Model
{
     protected $table = 'document';

    protected $fillable = [
        'name',
        'description',
        'url',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
