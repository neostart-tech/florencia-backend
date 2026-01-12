<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Personnel extends Model
{
    use HasUuid;

    protected $fillable = ['nom','prenom','tel','email'];

    public function horaires()
    {
        return $this->belongsToMany(Horaire::class, 'personnels_horaires');
    }
}
