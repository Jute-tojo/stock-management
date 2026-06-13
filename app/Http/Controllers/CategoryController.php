<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->categoryService->list());
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->store($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Category created.']);

        return back();
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->delete($category);

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Category deleted.']);
        } catch (\RuntimeException $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }

        return back();
    }
}
