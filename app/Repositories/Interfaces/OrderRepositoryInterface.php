<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function store(Request $request);
    public function indexForClient();
    public function indexForEmployee();
    public function show($id);
    public function cancel($id);
    public function updateStatus($id, $status);
}
