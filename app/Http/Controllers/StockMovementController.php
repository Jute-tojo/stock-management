<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Models\Product;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService,
    ) {}

    public function index(): Response
    {
        $search = request('search');

        return Inertia::render('StockMovement/Index', [
            'movements' => $this->stockMovementService->list($search),
            'products' => Product::select('id', 'name', 'sku')->orderBy('name')->get(),
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
