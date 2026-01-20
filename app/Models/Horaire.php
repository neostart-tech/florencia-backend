<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Horaire extends Model
{
    use HasUuid;

    protected $fillable = ['heure_debut', 'heure_fin', 'nbre_clients', 'jour_id', 'calendrier_id', 'service_id'];

    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }
    public function calendrier()
    {
        return $this->belongsTo(Calendrier::class);
    }
    public function personnels()
    {
        return $this->belongsToMany(Personnel::class, 'personnels_horaires');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
