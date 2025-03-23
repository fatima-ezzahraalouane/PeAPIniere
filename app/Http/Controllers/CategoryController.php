<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $repo;

    public function __construct(CategoryRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return response()->json($this->repo->index());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name'
        ]);

        return response()->json($this->repo->store($request), 201);
    }

    public function show(string $slug)
    {
        return response()->json($this->repo->show($slug));
    }

    public function update(Request $request, string $slug)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $slug . ',slug'
        ]);

        return response()->json($this->repo->update($request, $slug));
    }

    public function destroy(string $slug)
    {
        return $this->repo->destroy($slug);
    }
}
