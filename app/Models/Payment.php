<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    /**
     * Valid payment statuses, mirroring the enum in the payments migration.
     */
    public const STATUSES = ['pending', 'completed', 'failed', 'refunded'];

    protected $fillable = [
        'order_number',
        'payment_method',
        'subtotal',
        'tax',
        'total',
        'status',
        'transaction_reference',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Scope: payments that are still pending (useful for finding orphaned records).
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
