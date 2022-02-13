<?php

namespace App\Arma;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * Nom de la connection pour le modèle.
     *
     * @var string
     */
    protected $connection = 'a3f';

    /**
     * La primary key de la table pop dans la bdd est uid...
     *
     * @var string
     */
    protected $primaryKey = 'pid';

    protected $table = 'players';

    /**
     * Désactivez les horodatages car la bdd de pop n'a pas cette colonne.
     * Pour garder une trace, j'utiliserai "revisionable".
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'pid', 'steamid');
    }

    public function phone() {
        return $this->belongsTo(Phone::class, 'pid', 'pid');
    }

    public function vehicles()
    {
        return $this->belongsTo(Vehicle::class, 'pid', 'pid');
    }

    public function bnp()
    {
        return $this->belongsTo(Bnp::class, 'pid', 'pid');
    }
    public function identite()
    {
        return $this->belongsTo(Identite::class, 'pid', 'pid');
    }

    public function transactions()
    {
//        return $this->hasMany(Transaction::class, 'id_cliente', 'pid');
    }

    /**
     * Calcul de la carte d'identité complète avec lettre
     * https://archive.is/EIw9H.
     *
     * @return bool|string
     */
    public function getDniAttribute()
    {
        $numbers = substr($this->uid, -8);
        $resto = round($numbers % 23);
        $letter = '?';
        switch ($resto) {
            case 0:
                $letter = 'T';
                break;
            case 1:
                $letter = 'R';
                break;
            case 2:
                $letter = 'W';
                break;
            case 3:
                $letter = 'A';
                break;
            case 4:
                $letter = 'G';
                break;
            case 5:
                $letter = 'M';
                break;
            case 6:
                $letter = 'Y';
                break;
            case 7:
                $letter = 'F';
                break;
            case 8:
                $letter = 'P';
                break;
            case 9:
                $letter = 'D';
                break;
            case 10:
                $letter = 'X';
                break;
            case 11:
                $letter = 'B';
                break;
            case 12:
                $letter = 'N';
                break;
            case 13:
                $letter = 'J';
                break;
            case 14:
                $letter = 'Z';
                break;
            case 15:
                $letter = 'S';
                break;
            case 16:
                $letter = 'Q';
                break;
            case 17:
                $letter = 'V';
                break;
            case 18:
                $letter = 'H';
                break;
            case 19:
                $letter = 'L';
                break;
            case 20:
                $letter = 'C';
                break;
            case 21:
                $letter = 'K';
                break;
            default:
                $letter = 'Ñ';
        }

        return $numbers.$letter;
    }
}
