<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface PlantRepositoryInterface
{
    public function index();
    public function show(string $slug);
    public function store(Request $request);
    public function update(Request $request, string $slug);
    public function destroy(string $slug);
    public function getBySlug(string $slug);
}
