<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_program',
        'slug',
        'deskripsi',
        'ikon',
        'status_aktif',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Relasi ke paket/item katalog.
     */
    public function katalogs(): HasMany
    {
        return $this->hasMany(Katalog::class, 'program_id');
    }
}
