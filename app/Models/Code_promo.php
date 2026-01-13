<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Code_promo extends Model
{
    use HasUuid;
    protected $fillable = ['date_debut','date_fin','code','pourcentage'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_codes_promo', 'promo_id', 'user_id');
    }
}
