<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductImage extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_images';

    protected $fillable = [
        'product_id',
        'filename',
        'mime_type',
        'data',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];
}
