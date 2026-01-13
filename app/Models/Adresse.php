<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Adresse extends Model
{
    use HasUuid;

    protected $fillable = ['adresse','ville','code_postal','tel','user_id'];

    public function user(){ return $this->belongsTo(User::class); }
}
