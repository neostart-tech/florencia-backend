<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Sous_categorie extends Model
{
    use HasUuid;

    protected $fillable = ['libelle','categorie_id'];

    public function categorie(){ return $this->belongsTo(Categorie::class); }
    public function articles(){ return $this->hasMany(Article::class); }
}
