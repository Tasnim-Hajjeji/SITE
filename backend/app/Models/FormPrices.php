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
        'prix_tun_hebergement',
        'prix_eur_hebergement',
        'prix_article_tun',
        'prix_article_eur',
        'prix_acc_adulte_tun',
        'prix_acc_adulte_eur',
        'prix_acc_enfant_tun',
        'prix_acc_enfant_eur',
        'prix_nuit_supp_tun',
        'prix_nuit_supp_eur',
        'prix_single_supp_tun',
        'prix_single_supp_eur',
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
