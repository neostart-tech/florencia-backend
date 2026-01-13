<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'prix_total' => $this->prix_total,
            'statut' => $this->statut,

            'user' => $this->whenLoaded('user'),
            'details' => $this->whenLoaded('details'),
            'paiements' => $this->whenLoaded('paiements'),

            'created_at' => $this->created_at,
        ];
    }
}
