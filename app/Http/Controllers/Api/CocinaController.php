<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CocinaController extends Controller
{
    /**
     * GET /api/cocina/pedidos-pendientes
     * Returns orders with status 'pending', 'preparing', or 'ready', oldest first.
     */
    public function pendientes(): JsonResponse
    {
        $orders = Order::whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $orders,
        ], 200);
    }

    /**
     * PATCH /api/cocina/pedidos/{orderNumber}/estado
     * Update order status with transition validation.
     *
     * Allowed transitions:
     *   pending   -> preparing | cancelled
     *   preparing -> ready     | cancelled
     *   ready     -> delivered              (cancellation not allowed once food is ready)
     */
    public function actualizarEstado(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        $newStatus = $validated['status'];

        if (! $order->canTransitionTo($newStatus)) {
            return response()->json([
                'message' => "Invalid status transition from '{$order->status}' to '{$newStatus}'.",
                'allowed' => Order::STATUS_TRANSITIONS[$order->status] ?? [],
            ], 422);
        }

        $order->status = $newStatus;
        $order->save();

        // Sync payment status when the order reaches a terminal state
        if (in_array($newStatus, ['delivered', 'cancelled'], true)) {
            try {
                $payment = Payment::where('order_number', $orderNumber)->first();
                if ($payment) {
                    $paymentStatus = $newStatus === 'cancelled' ? 'refunded' : 'completed';
                    $payment->update(['status' => $paymentStatus]);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to update payment status after order status change', [
                    'order_number' => $orderNumber,
                    'new_status'   => $newStatus,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Order status updated.',
            'data'    => [
                'order_number' => $order->order_number,
                'status'       => $order->status,
                'updated_at'   => $order->updated_at,
            ],
        ], 200);
    }
}
