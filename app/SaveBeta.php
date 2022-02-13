<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaveBeta extends Model
{

    protected $table = 'savebeta';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
