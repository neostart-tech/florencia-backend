<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleVarianteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'article' => $this->whenLoaded('article'),
            'variante' => $this->whenLoaded('variante'),

            'created_at' => $this->created_at,
        ];
    }
}
