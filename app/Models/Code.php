<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Code extends Model
{
    protected $fillable = [
        'code',
        'person_name',
        'used',
        'used_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'used_at' => 'datetime',
    ];

    /**
     * Marquer le code comme utilisé
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => \Illuminate\Support\Carbon::now(),
        ]);
    }

    /**
     * Vérifier si le code est valide
     */
    public function isValid(): bool
    {
        return !$this->used;
    }

    /**
     * Obtenir l'URL du QR code
     */
    public function getQrUrlAttribute(): string
    {
        $filename = "qrcodes/{$this->code}.png";
        // Storage::url() retourne une URL relative, on doit la convertir en URL absolue
        $relativeUrl = \Illuminate\Support\Facades\Storage::url($filename);
        // Utiliser asset() pour obtenir l'URL complète avec le bon préfixe
        return asset($relativeUrl);
    }

    /**
     * Obtenir le chemin absolu du QR code
     */
    public function getQrPathAttribute(): string
    {
        $filename = "qrcodes/{$this->code}.png";
        return \Illuminate\Support\Facades\Storage::disk('public')->path($filename);
    }
}
