<?php

namespace App\Arma;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Nom de la connection pour le modèle.
     *
     * @var string
     */
    protected $connection = 'a3f';

    protected $table = 'movimientos_bancarios';

    protected $dates = [
        'timestamp',
    ];

    /**
     * Désactivez les horodatages car la bdd de pop n'a pas cette colonne.
     * Pour garder une trace, j'utiliserai "revisionable".
     *
     * @var bool
     */
    public $timestamps = false;

    public function player()
    {
        $this->belongsTo(Player::class, 'id_cliente', 'pid');
    }

    public function getTypeName()
    {
        $return = 'Autre';
        switch ($this->tipo) {
            case 0:
                $return = 'Retiré';
                break;
            case 1:
                $return = 'Entrez';
                break;
            case 2:
                $return = 'Paiement par carte';
                break;
            case 3:
                $return = 'Autres';
                break;
            case 4:
                $return = 'Transfert';
                break;
            case 5:
                $return = 'Transfert';
                break;
            default:
                $return = 'Autres';
        }

        return $return;
    }
}
