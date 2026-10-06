<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'category',
        'activity_date',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get full image URL helper attribute.
     */
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/'.$this->image_path);
    }
}
