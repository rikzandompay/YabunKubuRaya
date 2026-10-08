<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'header_image',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            self::generateStaticSitemap();
        });

        static::deleted(function () {
            self::generateStaticSitemap();
        });
    }

    public static function generateStaticSitemap(): void
    {
        try {
            $articles = self::published()
                ->select('id', 'slug', 'updated_at', 'published_at')
                ->orderByDesc('published_at')
                ->get();

            $content = view('sitemap', compact('articles'))->render();
            file_put_contents(public_path('sitemap.xml'), $content);
        } catch (\Throwable) {
            // Ignore if in migration or cli environment without database
        }
    }

    public function programCategory(): BelongsTo
    {
        return $this->belongsTo(ProgramCategory::class);
    }

    public function getHeaderImageUrlAttribute(): string
    {
        if (! $this->header_image) {
            return 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=800';
        }

        if (str_starts_with($this->header_image, 'http://') || str_starts_with($this->header_image, 'https://')) {
            return $this->header_image;
        }

        return asset('storage/'.$this->header_image);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeCategory(Builder $query, ?string $slug): Builder
    {
        if (! $slug || $slug === 'semua') {
            return $query;
        }

        return $query->whereHas('programCategory', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    public function scopeDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->whereDate('published_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('published_at', '<=', $to);
        }

        return $query;
    }
}
