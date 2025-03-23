<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        return Category::create([
            'name' => $request->name
        ]);
    }

    public function show(string $slug)
    {
        return Category::where('slug', $slug)->firstOrFail();
    }

    public function update(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->update(['name' => $request->name]);
        return $category;
    }

    public function destroy(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->delete();
        return response()->json(['message' => 'Catégorie supprimée avec succès.']);
    }
}