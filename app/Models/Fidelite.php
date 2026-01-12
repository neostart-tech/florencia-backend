<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Fidelite extends Model
{
    use HasUuid;

    protected $fillable = ['code','pourcentage','is_active','user_id'];

    public function users(){ return $this->belongsTo(User::class); }
}
