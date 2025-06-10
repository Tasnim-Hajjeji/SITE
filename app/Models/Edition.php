<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    protected $table = 'edition';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'place',
        'tun_price',
        'eur_price',
        'image_url',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class, 'edition_id');
    }

    public function importantDates()
    {
        return $this->hasMany(ImportantDate::class, 'edition_id');
    }

    public function intervenants()
    {
        return $this->hasMany(Intervenant::class, 'edition_id');
    }
}
