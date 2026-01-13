<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Article extends Model
{
    use HasUuid;

    protected $fillable = ['nom','prix','description','sous_categorie_id'];

    public function sousCategorie(){ return $this->belongsTo(Sous_categorie::class); }
    public function stock(){ return $this->hasOne(Stock::class); }
    public function images(){ return $this->hasMany(Image::class); }
    public function variantes(){ return $this->belongsToMany(Variante::class, 'articles_variantes'); }
    public function mouvements(){ return $this->hasMany(Stock_mouvement::class); }
}
