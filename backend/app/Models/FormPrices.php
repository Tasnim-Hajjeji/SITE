<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPrices extends Model
{
    protected $table = 'form_prices';

    protected $fillable = [
        'edition_id',
        'prix_tun',
        'prix_international',
        'prix_tun_sans_hebergement',
        'prix_article',
        'prix_acc_adulte',
        'prix_acc_enfant',
        'prix_nuit_supp',
        'prix_single_supp',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
