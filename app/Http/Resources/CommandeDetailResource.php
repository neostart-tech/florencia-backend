<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandeDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantite' => $this->quantite,
            'prix_unitaire' => $this->prix_unitaire,

            'article' => $this->whenLoaded('article'),
            'commande' => $this->whenLoaded('commande'),

            'created_at' => $this->created_at,
        ];
    }
}
