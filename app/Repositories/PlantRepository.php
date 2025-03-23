<?php

namespace App\Repositories;

use App\Models\Plant;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PlantRepositoryInterface;

class PlantRepository implements PlantRepositoryInterface
{
    public function index()
    {
        return Plant::with('category', 'images')->get();
    }

    public function show(string $slug)
    {
        return Plant::with('category', 'images')->where('slug', $slug)->firstOrFail();
    }

    public function store(Request $request)
    {
        $plant = Plant::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id
        ]);

        foreach ($request->images as $url) {
            $plant->images()->create(['url' => $url]);
        }

        return $plant->load('category', 'images');
    }

    public function update(Request $request, string $slug)
    {
        $plant = Plant::where('slug', $slug)->firstOrFail();

        $plant->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('images')) {
            foreach ($request->images as $url) {
                $plant->images()->create(['url' => $url]);
            }
        }

        return $plant->load('category', 'images');
    }

    public function destroy(string $slug)
    {
        $plant = Plant::where('slug', $slug)->firstOrFail();
        $plant->images()->delete();
        $plant->delete();

        return response()->json(['message' => 'Plante supprimée avec succès']);
    }

    public function getBySlug(string $slug)
    {
        return Plant::where('slug', $slug)->firstOrFail();
    }
}
