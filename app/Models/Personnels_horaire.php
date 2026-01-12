<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Personnels_horaire extends Model
{
    use HasUuid;


    protected $fillable = [
        'personnel_id',
        'horaire_id',
    ];

    public function personnels()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function horaires()
    {
        return $this->belongsTo(Horaire::class);
    }
}
