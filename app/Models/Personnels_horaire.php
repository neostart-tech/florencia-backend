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
        'service_id',
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
