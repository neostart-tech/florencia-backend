<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonnelHoraireResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'personnel' => $this->whenLoaded('personnel'),
            'horaire' => $this->whenLoaded('horaire'),

            'created_at' => $this->created_at,
        ];
    }
}
