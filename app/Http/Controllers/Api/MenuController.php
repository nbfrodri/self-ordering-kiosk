<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
class MenuController extends Controller
{
    /**
     * GET /api/menu
     * Returns all active categories with their available products and customizations.
     */
    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->orderBy('display_order')
            ->with([
                'products' => function ($query) {
                    $query->available()
                        ->orderBy('is_featured', 'desc')
                        ->orderBy('display_order')
                        ->orderBy('name')
                        ->with([
                            'customizations' => function ($q) {
                                $q->available()->orderBy('type')->orderBy('name');
                            },
                        ]);
                },
            ])
            ->get();

        return response()->json([
            'data' => $categories,
        ], 200);
    }
}
