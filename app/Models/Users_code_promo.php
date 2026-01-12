<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class UserCodePromo extends Model
{
    use HasUuid;


    protected $fillable = [
        'user_id',
        'promo_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function code_promos()
    {
        return $this->belongsTo(Code_promo::class, 'promo_id');
    }
}
