<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Users_code_promo extends Model
{
    use HasUuid;


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
