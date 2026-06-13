<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function list(): Collection
    {
        return Category::withCount('products')->orderBy('name')->get();
    }

    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function delete(Category $category): void
    {
        if ($category->products()->count() > 0) {
            throw new \RuntimeException('Cannot delete category with linked products.');
        }

        $category->delete();
    }

    public function deleteById(int $id): void
    {
        Category::findOrFail($id)->delete();
    }
}
