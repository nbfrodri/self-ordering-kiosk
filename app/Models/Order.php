<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'orders';

    protected $fillable = [
        'order_number',
        'status',
        'items',
        'payment_id',
        'customer_name',
        'notes',
        'estimated_preparation_minutes',
        'subtotal',
        'tax',
        'total',
        'payment_method',
    ];

    protected $casts = [
        'estimated_preparation_minutes' => 'integer',
    ];

    /**
     * Valid order statuses.
     */
    public const STATUSES = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];

    /**
     * Allowed status transitions.
     * Key = current status, value = allowed next statuses.
     */
    public const STATUS_TRANSITIONS = [
        'pending'   => ['preparing', 'cancelled'],
        'preparing' => ['ready', 'cancelled'],
        'ready'     => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::STATUS_TRANSITIONS[$this->status] ?? []);
    }
}
