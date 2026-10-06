<?php

namespace App\Models;

use App\Services\FinanceSummaryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $table = 'financial_transactions';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'category',
        'amount',
        'donor_id',
        'description',
        'transaction_date',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    /**
     * Scope untuk transaksi bertipe pemasukan.
     */
    public function scopePemasukan(Builder $query): Builder
    {
        return $query->where('type', 'pemasukan');
    }

    /**
     * Scope untuk memfilter berdasarkan kategori transaksi.
     */
    public function scopeKategori(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Relasi ke data donatur (opsional).
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donatur::class, 'donor_id');
    }

    /**
     * Label nama kategori transaksi yang diformat ramah baca.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'jumat_berkah' => 'Jumat Berkah',
            'donasi_bantuan' => 'Donasi Bantuan',
            'pembangunan_pondok_tahfidz' => 'Pembangunan Pondok Tahfidz',
            default => ucwords(str_replace('_', ' ', (string) $this->category)),
        };
    }

    /**
     * Invalidate cache ringkasan transparansi setiap kali ada mutasi data transaksi.
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
