<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\HasUuid;

class Image extends Model
{
    use HasUuid;

    protected $fillable = ['path'];

    protected $appends = ['url'];

    /**
     * Génère l'URL publique complète de l'image.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
