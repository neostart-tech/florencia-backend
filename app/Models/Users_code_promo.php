<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users_code_promo extends Model
{


    protected $fillable = [
        'user_id',
        'promo_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function code_promo()
    {
        return $this->belongsTo(Code_promo::class, 'promo_id');
    }
}
