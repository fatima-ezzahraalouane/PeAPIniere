<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function index();
    public function store(Request $request);
    public function show(string $slug);
    public function update(Request $request, string $slug);
    public function destroy(string $slug);
}