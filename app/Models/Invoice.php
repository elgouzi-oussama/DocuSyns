<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'reference_commande',
        'date_commande',
        'nom_fournisseur',
        'code_fournisseur',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'commande_par',
        'commande_a',
        'file',
        'statut',


    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
