<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnalyticsController extends Controller
{
    /**
     * Allowed analytics event types to prevent arbitrary data injection.
     */
    private const ALLOWED_EVENT_TYPES = [
        'page_view',
        'product_view',
        'add_to_cart',
        'remove_from_cart',
        'checkout_start',
        'checkout_complete',
        'payment_method_selected',
        'category_selected',
        'session_start',
        'session_end',
        'kiosk_idle',
        'kiosk_error',
    ];

    /**
     * POST /api/analiticas/eventos
     * Log an analytics event to MongoDB.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string', 'max:100', Rule::in(self::ALLOWED_EVENT_TYPES)],
            'data'       => ['nullable', 'array'],
            'data.*'     => ['nullable', 'string', 'max:1000'],
            'session_id' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_\-]+$/'],
        ]);

        $event = AnalyticsEvent::create([
            'event_type' => $validated['event_type'],
            'data'       => $validated['data'] ?? [],
            'session_id' => $validated['session_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Event logged.',
            'data'    => [
                'id'         => $event->id,
                'event_type' => $event->event_type,
                'created_at' => $event->created_at,
            ],
        ], 201);
    }

    /**
     * GET /api/analiticas/resumen
     * Return summary statistics:
     *  - Total orders (by status)
     *  - Average preparation time
     *  - Popular items (top 10 by quantity)
     *  - Revenue by payment method
     */
    public function index(): JsonResponse
    {
        // --- Order counts by status (MongoDB) ---
        $ordersByStatus = Order::raw(function ($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$status', 'count' => ['$sum' => 1]]],
                ['$sort'  => ['_id' => 1]],
            ]);
        });

        $statusCounts = [];
        foreach ($ordersByStatus as $row) {
            $statusCounts[$row['_id']] = $row['count'];
        }

        $totalOrders = array_sum($statusCounts);

        // --- Average ACTUAL preparation time (MongoDB) ---
        // Compute the mean difference between created_at and updated_at for
        // delivered orders, which reflects how long each order truly took.
        $avgPrepRaw = Order::raw(function ($collection) {
            return $collection->aggregate([
                ['$match' => ['status' => 'delivered']],
                [
                    '$project' => [
                        'actual_minutes' => [
                            '$divide' => [
                                ['$subtract' => ['$updated_at', '$created_at']],
                                60000, // milliseconds → minutes
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        '_id'     => null,
                        'avg_min' => ['$avg' => '$actual_minutes'],
                    ],
                ],
            ]);
        });

        $avgPrep = 0;
        foreach ($avgPrepRaw as $row) {
            $avgPrep = $row['avg_min'] ?? 0;
            break;
        }

        // --- Popular items (MongoDB aggregation on items array) ---
        // Filter out documents where product_name is null or empty to avoid
        // ghost entries. Also require the field to exist before grouping.
        $popularItemsRaw = Order::raw(function ($collection) {
            return $collection->aggregate([
                ['$unwind' => '$items'],
                [
                    '$match' => [
                        'items.product_name' => [
                            '$exists' => true,
                            '$nin'    => [null, ''],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        '_id'           => '$items.product_name',
                        'total_ordered' => ['$sum' => '$items.quantity'],
                    ],
                ],
                ['$sort'  => ['total_ordered' => -1]],
                ['$limit' => 10],
            ]);
        });

        $popularItems = [];
        foreach ($popularItemsRaw as $row) {
            if ($row['_id'] === null || $row['_id'] === '') {
                continue;
            }
            $popularItems[] = [
                'product_name'  => $row['_id'],
                'total_ordered' => $row['total_ordered'],
            ];
        }

        // --- Revenue by payment method (MySQL) ---
        $revenueByMethod = Payment::where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as transaction_count, SUM(total) as total_revenue')
            ->groupBy('payment_method')
            ->get()
            ->map(fn ($row) => [
                'payment_method'    => $row->payment_method,
                'transaction_count' => $row->transaction_count,
                'total_revenue'     => round((float) $row->total_revenue, 2),
            ]);

        return response()->json([
            'data' => [
                'total_orders'              => $totalOrders,
                'orders_by_status'          => $statusCounts,
                'avg_preparation_minutes'   => round((float) $avgPrep, 1),
                'popular_items'             => $popularItems,
                'revenue_by_payment_method' => $revenueByMethod,
            ],
        ], 200);
    }
}
