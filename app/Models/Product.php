<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image_url',
        'is_available',
        'is_featured',
        'display_order',
        'card_size',
        'preparation_time_minutes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
        'preparation_time_minutes' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customizations(): HasMany
    {
        return $this->hasMany(ProductCustomization::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }
}
