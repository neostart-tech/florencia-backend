<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Image extends Model
{
    use HasUuid;

    protected $fillable = ['path','article_id'];

    public function articles(){ return $this->belongsTo(Article::class); }
}
