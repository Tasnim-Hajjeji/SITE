<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    // Disable timestamps since your migration doesn't include them
    
    protected $table = 'edition';

    protected $fillable = [
        'name',
        'description_fr',
        'description_en',
        'start_date',
        'end_date',
        'place',
        'images_url',
        'dossier_sponso',
        'call_for_paper',
    ];

    protected $casts = [
        'start_date' => 'date',  
        'end_date' => 'date',    
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