<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Horaire extends Model
{
    use HasUuid;

    protected $fillable = ['heure_debut','heure_fin','nbre_clients','calendrier_id'];

    public function calendriers(){ return $this->belongsTo(Calendrier::class); }
    public function personnels(){ return $this->belongsToMany(Personnel::class, 'personnels_horaires'); }
    public function reservations(){ return $this->hasMany(Reservation::class); }
}
