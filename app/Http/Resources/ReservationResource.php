<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,

            'user' => $this->whenLoaded('user'),
            'service' => $this->whenLoaded('service'),
            'horaire' => $this->whenLoaded('horaire'),
            'paiements' => $this->whenLoaded('paiements'),

            'created_at' => $this->created_at,
        ];
    }
}
