<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantDate extends Model
{
    protected $table = 'important_dates';

    protected $fillable = [
        'title_fr',
        'title_en',
        'description_fr',
        'description_en',
        'date',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
