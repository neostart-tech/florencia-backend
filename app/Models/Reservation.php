<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Reservation extends Model
{
    use HasUuid;

    protected $fillable = ['code','service_id','horaire_id','user_id'];

    public function users(){ return $this->belongsTo(User::class); }
    public function services(){ return $this->belongsTo(Service::class); }
    public function horaires(){ return $this->belongsTo(Horaire::class); }
    public function paiements(){ return $this->morphMany(Paiement::class, 'owner'); }
}
