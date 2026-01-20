<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnels_horaire extends Model
{


    protected $fillable = [
        'personnel_id',
        'horaire_id',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function horaire()
    {
        return $this->belongsTo(Horaire::class);
    }
}
