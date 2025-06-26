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
        'est_tunisien',
        'participation',
        'accomodation',
        'etablissement',
        'num_enfant',
        'num_adulte',
        'supp_single',
        'supp_nuit',
        'prix_total',
        'edition_id',
        'methode_paie',
        'recu_paie'
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
