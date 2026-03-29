<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    /**
     * POST /api/pedidos
     * Validate and create a new order. Creates a Payment record in MySQL
     * and an Order document in MongoDB.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items'                   => ['required', 'array', 'min:1', 'max:50'],
            'items.*.product_id'      => ['required', 'integer', 'exists:products,id'],
            'items.*.product_name'    => ['required', 'string', 'max:255'],
            'items.*.quantity'        => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.unit_price'      => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'items.*.modifications'    => ['nullable', 'array'],
            'items.*.modifications.*'  => ['array'],
            'items.*.modifications.*.*' => ['string', 'max:255'],
            'items.*.subtotal'         => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'payment_method'          => ['required', Rule::in(['cash', 'credit_card', 'debit_card', 'mobile_pay'])],
            'subtotal'                => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'tax'                     => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'total'                   => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'customer_name'           => ['nullable', 'string', 'max:255'],
            'notes'                   => ['nullable', 'string', 'max:1000'],
        ]);

        // Sanitize free-text fields to prevent stored XSS
        $customerName = isset($validated['customer_name'])
            ? strip_tags($validated['customer_name'])
            : null;
        $notes = isset($validated['notes'])
            ? strip_tags($validated['notes'])
            : null;

        // Generate a unique order number using a timestamp prefix to reduce
        // collision probability, plus a retry loop as a safety net.
        $orderNumber = null;
        $maxAttempts = 10;
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $candidate = 'ORD-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
            if (! Payment::where('order_number', $candidate)->exists()) {
                $orderNumber = $candidate;
                break;
            }
        }

        if ($orderNumber === null) {
            return response()->json([
                'message' => 'Unable to generate a unique order number. Please try again.',
            ], 503);
        }

        // Look up actual preparation times from the database for ordered products
        $productIds = collect($validated['items'])->pluck('product_id')->unique()->all();
        $prepTimes = Product::whereIn('id', $productIds)
            ->pluck('preparation_time_minutes', 'id');

        // Estimate total preparation time using real product data.
        // Falls back to 5 minutes per item if a product is not found.
        $estimatedMinutes = collect($validated['items'])
            ->sum(function ($item) use ($prepTimes) {
                $prepTime = $prepTimes[$item['product_id']] ?? 5;
                return ($item['quantity'] ?? 1) * $prepTime;
            });
        $estimatedMinutes = min($estimatedMinutes, 60);

        // Use a MySQL transaction for the Payment, then attempt MongoDB write.
        // If MongoDB fails, roll back the payment to 'failed' status.
        $payment = null;
        $order = null;

        try {
            $payment = DB::connection('mysql')->transaction(function () use ($orderNumber, $validated) {
                return Payment::create([
                    'order_number'    => $orderNumber,
                    'payment_method'  => $validated['payment_method'],
                    'subtotal'        => $validated['subtotal'],
                    'tax'             => $validated['tax'],
                    'total'           => $validated['total'],
                    'status'          => 'pending',
                ]);
            });

            // Persist order document in MongoDB
            $order = Order::create([
                'order_number'                  => $orderNumber,
                'status'                        => 'pending',
                'items'                         => $validated['items'],
                'payment_id'                    => $payment->id,
                'customer_name'                 => $customerName,
                'notes'                         => $notes,
                'estimated_preparation_minutes' => $estimatedMinutes,
                'subtotal'                      => $validated['subtotal'],
                'tax'                           => $validated['tax'],
                'total'                         => $validated['total'],
                'payment_method'                => $validated['payment_method'],
            ]);

            // Mark the payment as completed for simulated (non-gateway) payments.
            // In production with a real payment gateway, this would happen after
            // receiving a webhook/callback confirmation instead.
            $payment->update(['status' => 'completed']);

        } catch (\Throwable $e) {
            Log::error('Order creation failed', [
                'order_number' => $orderNumber,
                'error'        => $e->getMessage(),
                'trace'        => $e->getTraceAsString(),
            ]);

            // If the Payment was created but the MongoDB Order write failed,
            // mark the payment as failed so it is not left dangling as 'pending'.
            if ($payment !== null && $order === null) {
                try {
                    $payment->update(['status' => 'failed']);
                } catch (\Throwable $innerEx) {
                    Log::critical('Failed to mark payment as failed after order creation error', [
                        'payment_id' => $payment->id,
                        'error'      => $innerEx->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'message' => 'An error occurred while creating the order. Please try again.',
            ], 500);
        }

        return response()->json([
            'message' => 'Order created successfully.',
            'data'    => [
                'order_number'                  => $orderNumber,
                'order_id'                      => $order->id,
                'status'                        => $order->status,
                'estimated_preparation_minutes' => $order->estimated_preparation_minutes,
                'payment'                       => [
                    'id'             => $payment->id,
                    'method'         => $payment->payment_method,
                    'subtotal'       => $payment->subtotal,
                    'tax'            => $payment->tax,
                    'total'          => $payment->total,
                    'status'         => $payment->status,
                ],
            ],
        ], 201);
    }

    /**
     * GET /api/pedidos/{orderNumber}/estado
     * Return order status by order_number.
     */
    public function show(string $orderNumber): JsonResponse
    {
        // Validate the order number format to prevent arbitrary lookups
        if (! preg_match('/^ORD-[A-Z0-9\-]{5,20}$/', $orderNumber)) {
            return response()->json(['message' => 'Invalid order number format.'], 400);
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $payment = Payment::where('order_number', $orderNumber)->first();

        return response()->json([
            'data' => [
                'order_number'                  => $order->order_number,
                'status'                        => $order->status,
                'estimated_preparation_minutes' => $order->estimated_preparation_minutes,
                'customer_name'                 => $order->customer_name,
                'notes'                         => $order->notes,
                'items'                         => $order->items,
                'payment_status'                => $payment?->status,
                'created_at'                    => $order->created_at,
            ],
        ], 200);
    }

    /**
     * GET /api/pedidos
     * List all orders, paginated.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = min(max($perPage, 1), 100);

        $orders = Order::orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data'       => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ], 200);
    }
}
