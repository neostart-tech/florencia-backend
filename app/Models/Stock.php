<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Stock extends Model
{
    use HasUuid;

    protected $fillable = ['quantite','article_id'];

    public function article(){ return $this->belongsTo(Article::class); }
}
