<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participant';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo_url',
        'edition_id',
        
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_participant', 'participant_id', 'program_id');
    }
}
