<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Paiement extends Model
{
    use HasUuid;

    protected $fillable = ['moyen_paiement','reference_transaction','statut'];

    public function owner()
    {
        return $this->morphTo();
    }
}
