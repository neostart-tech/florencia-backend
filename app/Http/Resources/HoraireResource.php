<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HoraireResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'heure_debut' => $this->heure_debut,
            'heure_fin' => $this->heure_fin,
            'nbre_clients' => $this->nbre_clients,

            'jour' => $this->whenLoaded('jour'),
            'calendrier' => $this->whenLoaded('calendrier'),
            'personnels' => $this->whenLoaded('personnels'),
            'reservations' => $this->whenLoaded('reservations'),

            'created_at' => $this->created_at,
        ];
    }
}
