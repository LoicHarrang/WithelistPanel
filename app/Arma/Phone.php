<?php

namespace App\Arma;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    /**
     * Nom de la connection pour le modèle.
     *
     * @var string
     */
    protected $connection = 'a3f_s0';
    protected $table = 'telephonie';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function num() {
        return $this->belongsTo(Player::class, 'pid', 'steamid');
    }

}
