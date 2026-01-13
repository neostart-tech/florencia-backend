<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SousCategorieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,

            'categorie' => $this->whenLoaded('categorie'),
            'articles' => $this->whenLoaded('articles'),

            'created_at' => $this->created_at,
        ];
    }
}
