<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\HasUuid;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom','email','tel',
        'password','role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(){ return $this->belongsTo(Role::class); }
    public function commandes(){ return $this->hasMany(Commande::class); }
    public function reservations(){ return $this->hasMany(Reservation::class); }
    public function adresses(){ return $this->hasMany(Adresse::class); }
    public function fidelites(){ return $this->hasOne(Fidelite::class); }

    public function code_promos()
    {
        return $this->belongsToMany(Code_promo::class, 'users_code_promos', 'user_id', 'promo_id');
    }
}
