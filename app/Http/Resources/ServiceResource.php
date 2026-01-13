<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'type' => $this->type,
            'duree' => $this->duree,

            'reservations' => $this->whenLoaded('reservations'),

            'created_at' => $this->created_at,
        ];
    }
}
