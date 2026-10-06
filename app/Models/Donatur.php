<?php

namespace App\Models;

use App\Services\FinanceSummaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donatur extends Model
{
    use HasFactory;

    protected $table = 'donatur';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_donatur',
        'nomor_hp',
        'tipe_donatur',
    ];

    /**
     * Relasi ke transaksi keuangan yayasan yang bersumber dari donatur ini.
     */
    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'donor_id');
    }

    /**
     * Invalidate cache ringkasan transparansi donasi setiap kali data donatur berubah.
     */
    protected static function booted(): void
    {
        static::saved(function (): void {
            FinanceSummaryService::clearCache();
        });

        static::deleted(function (): void {
            FinanceSummaryService::clearCache();
        });
    }
}
