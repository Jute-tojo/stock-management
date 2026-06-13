<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Services\ProductService;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService,
        private readonly ProductService $productService,
    ) {}

    public function index(): Response
    {
        $search = request('search');

        return Inertia::render('StockMovement/Index', [
            'movements' => $this->stockMovementService->list($search),
            'products' => $this->productService->listAll(),
            'filters' => $search,
        ]);
    }

    public function store(StockMovementRequest $request): RedirectResponse
    {
        $this->stockMovementService->store($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Stock movement recorded.']);

        return back();
    }
}
