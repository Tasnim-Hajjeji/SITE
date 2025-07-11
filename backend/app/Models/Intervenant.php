<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervenant extends Model
{
    protected $table = 'intervenant';

    protected $fillable = [
        'full_name',
        'description_fr',
        'description_en',
        'profession_fr',
        'profession_en',
        'image_url',
        'edition_id',
        'institut',
        'code_pays'
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_intervenant', 'intervenant_id', 'program_id');
    }
}
