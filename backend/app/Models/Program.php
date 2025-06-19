<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';

    protected $fillable = [
        'name_fr',
        'name_en',
        'description_fr',
        'description_en',
        'time_start',
        'time_end',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }

    public function intervenants()
    {
        return $this->belongsToMany(Intervenant::class, 'program_intervenant', 'program_id', 'intervenant_id');
    }
}
