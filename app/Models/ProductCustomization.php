<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCustomization extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'product_id',
        'name',
        'type',
        'price_modifier',
        'is_available',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }
}
