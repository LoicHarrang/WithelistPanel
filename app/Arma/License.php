<?php

namespace App\Arma;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    /**
     * Nom de la connection pour le modèle.
     *
     * @var string
     */
    protected $connection = 'a3f';

    public $timestamps = false;

    public function player() {
        return $this->belongsTo(Player::class, 'uid', 'steamid');
    }

}
