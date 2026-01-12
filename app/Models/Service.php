<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Service extends Model
{
    use HasUuid;

    protected $fillable = ['nom','type','duree'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
