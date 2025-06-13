<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    // Disable timestamps since your migration doesn't include them
    
    protected $table = 'edition';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'place',
        'tun_price',
        'eur_price',
        'images_url',
    ];

    protected $casts = [
        'start_date' => 'date',  // Changed from 'datetime' to 'date' to match migration
        'end_date' => 'date',    // Changed from 'datetime' to 'date' to match migration
        'images_url' => 'array'
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