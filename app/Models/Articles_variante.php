<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Articles_variante extends Model
{
    use HasUuid;


    protected $fillable = [
        'article_id',
        'variante_id',
    ];

    public function articles()
    {
        return $this->belongsTo(Article::class);
    }

    public function variantes()
    {
        return $this->belongsTo(Variante::class);
    }
}
