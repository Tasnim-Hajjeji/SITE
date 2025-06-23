<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $table = 'sponsor';
    protected $fillable = [
        'name',
        'adresse',
        'email',
        'phone',
        'description',
        'logo',
        'etat',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
