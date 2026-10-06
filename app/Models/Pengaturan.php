<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kunci',
        'nilai',
        'kelompok',
    ];

    /**
     * Helper untuk mengambil nilai pengaturan berdasarkan kunci.
     */
    public static function ambil(string $kunci, ?string $bawaan = null): ?string
    {
        return static::where('kunci', $kunci)->value('nilai') ?? $bawaan;
    }
}
