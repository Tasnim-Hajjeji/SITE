<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'image_url',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}