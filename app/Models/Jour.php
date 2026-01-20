<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Jour extends Model
{
    use HasUuid;
    protected $fillable = ['nom', 'numero'];

    public function horaires()
    {
        return $this->hasMany(Horaire::class);
    }

}
