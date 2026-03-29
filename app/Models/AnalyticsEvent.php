<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'analytics_events';

    protected $fillable = [
        'event_type',
        'data',
        'session_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
