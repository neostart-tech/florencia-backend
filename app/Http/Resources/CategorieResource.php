<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,

            'sous_categories' => $this->whenLoaded('sousCategories'),

            'created_at' => $this->created_at,
        ];
    }
}
