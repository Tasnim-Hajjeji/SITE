<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participant';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'fonction',
        'tel',
        'pays',
        'etablissement',
        'num_enfant',
        'num_adulte',
        'supp_single',
        'supp_nuit',
        'edition_id',
        
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
