<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prix' => $this->prix,
            'description' => $this->description,

            // Relations
            'sous_categorie' => $this->whenLoaded('sousCategorie'),
            'stock' => $this->whenLoaded('stock'),
            'images' => $this->whenLoaded('images'),
            'variantes' => $this->whenLoaded('variantes'),
            'mouvements' => $this->whenLoaded('mouvements'),

            'created_at' => $this->created_at,
        ];
    }
}
