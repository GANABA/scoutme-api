<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeInteraction extends Model
{
    //
    protected $fillable = [
        'emetteur_id',
        'recepteur_id',
        'message',
        'statut',
    ];

    public function emetteur()
    {
        return $this->belongsTo(User::class, 'emetteur_id');
    }

    public function recepteur()
    {
        return $this->belongsTo(User::class, 'recepteur_id');
    }
}
