<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Calendrier extends Model
{
    use HasUuid;

    protected $fillable = ['debut','fin','is_active'];

    public function horaires()
    {
        return $this->hasMany(Horaire::class);
    }
}
