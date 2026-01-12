<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Variante extends Model
{
    use HasUuid;

    protected $fillable = ['libelle'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_variantes');
    }
}
