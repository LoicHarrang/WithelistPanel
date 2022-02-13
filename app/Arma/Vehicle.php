<?php

namespace App\Arma;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * Nom de la connection pour le modèle.
     *
     * @var string
     */
    protected $connection = 'a3f';
    protected $table = 'vehicles';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function player()
    {
        return $this->belongsTo(Player::class, 'pid', 'steamid');
    }
}
