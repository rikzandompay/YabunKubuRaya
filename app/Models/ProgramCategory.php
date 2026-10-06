<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function getCoverImageUrlAttribute(): string
    {
        if (! $this->cover_image) {
            return 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=800';
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, 'images/')) {
            return asset($this->cover_image);
        }

        return asset('storage/'.$this->cover_image);
    }
}
