<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Katalog extends Model
{
    use HasFactory;

    protected $table = 'katalog';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'program_id',
        'judul',
        'slug',
        'deskripsi',
        'gambar',
        'target_penerima',
        'status',
    ];

    /**
     * Relasi ke program induk.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Accessor untuk URL gambar katalog.
     */
    public function getGambarUrlAttribute(): string
    {
        if (! $this->gambar) {
            return asset('images/santri-yabun.webp');
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        if (str_starts_with($this->gambar, 'images/')) {
            return asset($this->gambar);
        }

        return asset('storage/'.$this->gambar);
    }
}
