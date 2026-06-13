<?php

namespace App\Http\Controllers;

use App\Enums\ProductUnit;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService,
    ) {}

    public function index(): Response
    {
        $this->authorize('viewAny', Product::class);

        $search = request('search');

        return Inertia::render('Product/Index', [
            'products' => $this->productService->list($search, 10),
            'categories' => $this->categoryService->list(),
            'units' => ProductUnit::options(),
            'filters' => $search,
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $this->productService->store($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Product created.']);

        return back();
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $this->productService->update($product, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Product updated.']);

        return back();
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Product deleted.']);

        return back();
    }
}
