<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
//    protected $connection = 'a3f';

    /**
     * La primary key de la bdd est uid...
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * Désactivez les horodatages car le la bdd n'a pas cette colonne.
     * Pour garder une trace, j'utiliserai "revisionable".
     *
     * @var bool
     */
    public $timestamps = false;
}
