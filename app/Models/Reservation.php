<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Reservation extends Model
{
    use HasUuid;

    protected $fillable = ['code','service_id','horaire_id','user_id'];

    public function user(){ return $this->belongsTo(User::class); }
    public function service(){ return $this->belongsTo(Service::class); }
    public function horaire(){ return $this->belongsTo(Horaire::class); }
    public function paiements(){ return $this->morphMany(Paiement::class, 'owner'); }
}
