<?php

namespace App\Services;

use App\Enums\StockMovement as StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;

class StockMovementService
{
    public function list(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return StockMovement::with('product:id,name,sku', 'user:id,name')
            ->when($search, fn($q) => $q->whereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%")))
            ->latest()
            ->paginate($perPage);
    }

    public function store(array $data): StockMovement
    {
        $data['user_id'] = auth()->id();

        $movement = StockMovement::create($data);

        $product = Product::findOrFail($data['product_id']);

        if ($data['type'] === StockMovementType::IN->value) {
            $product->increment('quantity', $data['quantity']);
        } elseif ($data['type'] === StockMovementType::OUT->value) {
            $product->decrement('quantity', $data['quantity']);
        }

        return $movement;
    }
}
