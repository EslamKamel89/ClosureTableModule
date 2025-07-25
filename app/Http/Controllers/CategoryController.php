<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller {
    public function index() {
        $tree = Cache::remember('category-tree', '3600', function () {
            return Category::with('children')
                ->whereHas('ancestors', function ($q) {
                    $q->where('ancestor_id', 1);
                })
                ->orWhere('id', 1)
                ->get()
                ->map(fn($cat) => $this->transformToTree($cat));
        });
        return inertia('Categories/Index', ['tree' => $tree]);
    }
    private function transformToTree(Category $category): array {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'is_leaf' => $category->isLeaf(),
            'product_count' => $category->products()->count(),
            'children' => $category->children->map(
                fn($child) => $this->transformToTree($child)
            )
        ];
    }
    public function breadcrumbs($id) {
        $category = Category::with(['ancestors'])->findOrFail($id);
        // return $category;
        return response()->json([
            'breadcrumbs' => $category->breadcrumbs()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug
                ])
                ->toArray(),
            'self' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'is_leaf' => $category->isLeaf(),
            ]
        ]);
    }
}
