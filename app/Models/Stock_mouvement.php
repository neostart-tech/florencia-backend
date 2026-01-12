<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Stock_mouvement extends Model
{
    use HasUuid;

    protected $fillable = ['type','quantite','commentaire','article_id'];

    public function articles(){ return $this->belongsTo(Article::class); }
}
