<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function list(?string $keyword = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->applySearch(Product::with('category'), $keyword)
            ->latest()
            ->paginate($perPage);
    }

    public function listAll(): array
    {
        return Product::select('id', 'name', 'sku', 'image', 'quantity', 'unit')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    private function applySearch(Builder $query, ?string $keyword): Builder
    {
        if ($keyword) {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }

    public function store(array $data): Product
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $data['image']->store('products', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
    }
}
