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

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function variante()
    {
        return $this->belongsTo(Variante::class);
    }
}
