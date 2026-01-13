<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMouvementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'quantite' => $this->quantite,
            'commentaire' => $this->commentaire,

            'article' => $this->whenLoaded('article'),

            'created_at' => $this->created_at,
        ];
    }
}
