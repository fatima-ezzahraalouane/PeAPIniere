<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\PlantRepositoryInterface;

class PlantController extends Controller
{
    protected $repo;

    public function __construct(PlantRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return response()->json($this->repo->index());
    }

    public function show(string $slug)
    {
        return response()->json($this->repo->show($slug));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:plants,name',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images'      => 'required|array|max:4',
            'images.*'    => 'required|url'
        ]);

        // Vérification manuelle au cas où quelqu’un modifie la règle `max:4`
        if (count($request->images) > 4) {
            return response()->json([
                'error' => 'Vous ne pouvez ajouter que 4 images maximum.'
            ], 422);
        }

        return response()->json($this->repo->store($request), 201);
    }

    public function update(Request $request, string $slug)
    {
        $request->validate([
            'name'        => 'required|string|unique:plants,name,' . $slug . ',slug',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images'      => 'nullable|array',
            'images.*'    => 'nullable|url'
        ]);

        $plant = $this->repo->getBySlug($slug); // On récupère ici pour valider les images

        $currentImagesCount = $plant->images()->count();
        $newImagesCount = is_array($request->images) ? count($request->images) : 0;
        $total = $currentImagesCount + $newImagesCount;

        if ($total > 4) {
            return response()->json([
                'error' => 'Vous ne pouvez pas dépasser 4 images par plante. (actuelles : ' . $currentImagesCount . ')'
            ], 422);
        }

        return response()->json($this->repo->update($request, $slug));
    }


    public function destroy(string $slug)
    {
        return $this->repo->destroy($slug);
    }
}
