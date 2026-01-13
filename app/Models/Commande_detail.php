<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Commande_detail extends Model
{
    use HasUuid;

    protected $fillable = ['quantite','prix_unitaire','article_id','commande_id'];

    public function article(){ return $this->belongsTo(Article::class); }
    public function commande(){ return $this->belongsTo(Commande::class); }
}
