<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'tel' => $this->tel,

            'role' => $this->whenLoaded('role'),
            'commandes' => $this->whenLoaded('commandes'),
            'reservations' => $this->whenLoaded('reservations'),
            'adresses' => $this->whenLoaded('adresses'),
            'fidelite' => $this->whenLoaded('fidelite'),
            'code_promos' => $this->whenLoaded('code_promos'),

            'created_at' => $this->created_at,
        ];
    }
}
