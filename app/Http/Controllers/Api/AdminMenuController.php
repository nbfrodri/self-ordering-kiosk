<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCustomization;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdminMenuController extends Controller
{
    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function clearMenuCache(): void
    {
        Cache::forget('menu:active_categories');
    }

    private function jsonError(string $message, int $status = 422, array $errors = []): JsonResponse
    {
        $body = ['message' => $message];
        if ($errors) {
            $body['errors'] = $errors;
        }
        return response()->json($body, $status);
    }

    // -------------------------------------------------------------------------
    // Categories
    // -------------------------------------------------------------------------

    /**
     * GET /api/admin/categories
     * List all categories with product count, ordered by display_order.
     */
    public function categoriesIndex(): JsonResponse
    {
        $categories = Category::orderBy('display_order')
            ->withCount('products')
            ->get();

        return response()->json(['data' => $categories]);
    }

    /**
     * POST /api/admin/categories
     */
    public function categoriesStore(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'name'          => 'required|string|max:120',
            'description'   => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return $this->jsonError('Validation failed.', 422, $v->errors()->toArray());
        }

        $category = Category::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'display_order' => $request->input('display_order', 0),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        $this->clearMenuCache();

        return response()->json(['data' => $category->loadCount('products')], 201);
    }

    /**
     * PUT /api/admin/categories/{id}
     */
    public function categoriesUpdate(Request $request, int $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->jsonError('Category not found.', 404);
        }

        $v = Validator::make($request->all(), [
            'name'          => 'sometimes|required|string|max:120',
            'description'   => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return $this->jsonError('Validation failed.', 422, $v->errors()->toArray());
        }

        $category->fill($v->validated())->save();
        $this->clearMenuCache();

        return response()->json(['data' => $category->loadCount('products')]);
    }

    /**
     * DELETE /api/admin/categories/{id}
     * Cascade-deletes all products (and their customizations + images) in the category.
     */
    public function categoriesDestroy(int $id): JsonResponse
    {
        $category = Category::with('products')->find($id);
        if (!$category) {
            return $this->jsonError('Category not found.', 404);
        }

        DB::transaction(function () use ($category) {
            foreach ($category->products as $product) {
                $this->deleteProductImage($product->id);
                $product->customizations()->delete();
                $product->delete();
            }
            $category->delete();
        });

        $this->clearMenuCache();

        return response()->json(['message' => 'Category deleted.']);
    }

    // -------------------------------------------------------------------------
    // Products
    // -------------------------------------------------------------------------

    /**
     * GET /api/admin/products
     * List all products with category and customizations (no availability filter).
     */
    public function productsIndex(): JsonResponse
    {
        $products = Product::with(['category', 'customizations'])
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $products]);
    }

    /**
     * POST /api/admin/products
     */
    public function productsStore(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'name'                     => 'required|string|max:160',
            'description'              => 'nullable|string|max:1000',
            'price'                    => 'required|numeric|min:0',
            'category_id'              => 'required|integer|exists:categories,id',
            'preparation_time_minutes' => 'nullable|integer|min:0|max:999',
            'is_available'             => 'nullable|boolean',
            'is_featured'              => 'nullable|boolean',
            'display_order'            => 'nullable|integer|min:0|max:999',
            'card_size'                => 'nullable|string|in:standard,wide,tall',
        ]);

        if ($v->fails()) {
            return $this->jsonError('Validation failed.', 422, $v->errors()->toArray());
        }

        $product = Product::create([
            'name'                     => $request->name,
            'description'              => $request->description,
            'price'                    => $request->price,
            'category_id'              => $request->category_id,
            'preparation_time_minutes' => $request->input('preparation_time_minutes', 10),
            'is_available'             => $request->boolean('is_available', true),
            'is_featured'              => $request->boolean('is_featured', false),
            'display_order'            => $request->input('display_order', 0),
            'card_size'                => $request->input('card_size', 'standard'),
        ]);

        $this->clearMenuCache();

        return response()->json(['data' => $product->load(['category', 'customizations'])], 201);
    }

    /**
     * PUT /api/admin/products/{id}
     */
    public function productsUpdate(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->jsonError('Product not found.', 404);
        }

        $v = Validator::make($request->all(), [
            'name'                     => 'sometimes|required|string|max:160',
            'description'              => 'nullable|string|max:1000',
            'price'                    => 'sometimes|required|numeric|min:0',
            'category_id'              => 'sometimes|required|integer|exists:categories,id',
            'preparation_time_minutes' => 'nullable|integer|min:0|max:999',
            'is_available'             => 'nullable|boolean',
            'is_featured'              => 'nullable|boolean',
            'display_order'            => 'nullable|integer|min:0|max:999',
            'card_size'                => 'nullable|string|in:standard,wide,tall',
        ]);

        if ($v->fails()) {
            return $this->jsonError('Validation failed.', 422, $v->errors()->toArray());
        }

        $product->fill($v->validated())->save();
        $this->clearMenuCache();

        return response()->json(['data' => $product->load(['category', 'customizations'])]);
    }

    /**
     * DELETE /api/admin/products/{id}
     */
    public function productsDestroy(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->jsonError('Product not found.', 404);
        }

        DB::transaction(function () use ($product) {
            $this->deleteProductImage($product->id);
            $product->customizations()->delete();
            $product->delete();
        });

        $this->clearMenuCache();

        return response()->json(['message' => 'Product deleted.']);
    }

    // -------------------------------------------------------------------------
    // Product Images
    // -------------------------------------------------------------------------

    /**
     * POST /api/admin/products/{id}/image
     *
     * Accepts either:
     *   - multipart/form-data with key "image" (file upload)
     *   - JSON body with key "image" containing a data-URI or raw base64 string
     *     e.g. "data:image/jpeg;base64,/9j/..." or just "/9j/..."
     */
    public function imageStore(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->jsonError('Product not found.', 404);
        }

        $base64Data = null;
        $mimeType   = 'image/jpeg';
        $filename   = 'product_' . $id . '.jpg';

        // ---- Multipart file upload ----
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if (!$file->isValid()) {
                return $this->jsonError('Uploaded file is invalid.');
            }

            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mimeType = $file->getMimeType();

            if (!in_array($mimeType, $allowedMimes, true)) {
                return $this->jsonError('Only JPEG, PNG, WebP, and GIF images are allowed.');
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                return $this->jsonError('Image must be 5 MB or smaller.');
            }

            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $ext        = $file->getClientOriginalExtension() ?: 'jpg';
            $filename   = 'product_' . $id . '.' . $ext;

        // ---- JSON base64 upload ----
        } elseif ($request->has('image')) {
            $raw = $request->input('image');

            // Strip data-URI prefix if present: data:image/png;base64,<data>
            if (str_contains($raw, ';base64,')) {
                [$meta, $raw] = explode(';base64,', $raw, 2);
                $mimeType = str_replace('data:', '', $meta);
            }

            // Validate it decodes successfully
            if (base64_decode($raw, true) === false) {
                return $this->jsonError('Invalid base64 image data.');
            }

            $base64Data = $raw;
            $ext = match ($mimeType) {
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };
            $filename = 'product_' . $id . '.' . $ext;
        } else {
            return $this->jsonError('No image provided. Send a file under the "image" key or a base64 string.');
        }

        // Remove any existing image document for this product
        $this->deleteProductImage($id);

        // Store in MongoDB
        ProductImage::create([
            'product_id' => $id,
            'filename'   => $filename,
            'mime_type'  => $mimeType,
            'data'       => $base64Data,
        ]);

        // Update MySQL product record so the kiosk can reference the image
        $product->image_url = '/api/products/' . $id . '/image';
        $product->save();

        $this->clearMenuCache();

        return response()->json([
            'message'   => 'Image uploaded successfully.',
            'image_url' => $product->image_url,
        ], 201);
    }

    /**
     * GET /api/products/{id}/image
     * Public endpoint — serves the image binary from MongoDB.
     * Cache-Control header is set for 1 hour; the browser won't re-request it needlessly.
     */
    public function imageShow(int $id): Response
    {
        $image = ProductImage::where('product_id', $id)->first();

        if (!$image) {
            // Return a 1×1 transparent GIF as placeholder so <img> tags don't 404
            $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            return response($gif, 200)
                ->header('Content-Type', 'image/gif')
                ->header('Cache-Control', 'public, max-age=60');
        }

        $binary = base64_decode($image->data);

        $etag = '"' . md5($image->data) . '"';

        // Support conditional requests — return 304 if the browser already has this version
        $ifNoneMatch = request()->header('If-None-Match');
        if ($ifNoneMatch === $etag) {
            return response('', 304)
                ->header('ETag', $etag)
                ->header('Cache-Control', 'no-cache, must-revalidate');
        }

        return response($binary, 200)
            ->header('Content-Type', $image->mime_type)
            ->header('Content-Length', strlen($binary))
            ->header('Cache-Control', 'public, max-age=300, must-revalidate')
            ->header('ETag', $etag);
    }

    /**
     * DELETE /api/admin/products/{id}/image
     */
    public function imageDestroy(int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->jsonError('Product not found.', 404);
        }

        $deleted = $this->deleteProductImage($id);
        if (!$deleted) {
            return $this->jsonError('No image found for this product.', 404);
        }

        $product->image_url = null;
        $product->save();

        $this->clearMenuCache();

        return response()->json(['message' => 'Image deleted.']);
    }

    /**
     * Remove the MongoDB image document for a product.
     * Returns true if a document was deleted, false if none existed.
     */
    private function deleteProductImage(int $productId): bool
    {
        $deleted = ProductImage::where('product_id', $productId)->delete();
        return $deleted > 0;
    }

    // -------------------------------------------------------------------------
    // Customizations
    // -------------------------------------------------------------------------

    /**
     * POST /api/admin/products/{id}/customizations
     */
    public function customizationsStore(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->jsonError('Product not found.', 404);
        }

        $v = Validator::make($request->all(), [
            'name'           => 'required|string|max:120',
            'type'           => 'required|string|max:80',
            'price_modifier' => 'nullable|numeric',
            'is_available'   => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return $this->jsonError('Validation failed.', 422, $v->errors()->toArray());
        }

        $customization = $product->customizations()->create([
            'name'           => $request->name,
            'type'           => $request->type,
            'price_modifier' => $request->input('price_modifier', 0),
            'is_available'   => $request->boolean('is_available', true),
        ]);

        $this->clearMenuCache();

        return response()->json(['data' => $customization], 201);
    }

    /**
     * PUT /api/admin/customizations/{id}
     */
    public function customizationsUpdate(Request $request, int $id): JsonResponse
    {
        $customization = ProductCustomization::find($id);
        if (!$customization) {
            return $this->jsonError('Customization not found.', 404);
        }

        $v = Validator::make($request->all(), [
            'name'           => 'sometimes|required|string|max:120',
            'type'           => 'sometimes|required|string|max:80',
            'price_modifier' => 'nullable|numeric',
            'is_available'   => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return $this->jsonError('Validation failed.', 422, $v->errors()->toArray());
        }

        $customization->fill($v->validated())->save();
        $this->clearMenuCache();

        return response()->json(['data' => $customization]);
    }

    /**
     * DELETE /api/admin/customizations/{id}
     */
    public function customizationsDestroy(int $id): JsonResponse
    {
        $customization = ProductCustomization::find($id);
        if (!$customization) {
            return $this->jsonError('Customization not found.', 404);
        }

        $customization->delete();
        $this->clearMenuCache();

        return response()->json(['message' => 'Customization deleted.']);
    }

    // -------------------------------------------------------------------------
    // Cache
    // -------------------------------------------------------------------------

    /**
     * POST /api/admin/cache/clear
     */
    public function cacheClear(): JsonResponse
    {
        $this->clearMenuCache();
        return response()->json(['message' => 'Menu cache cleared.']);
    }
}
