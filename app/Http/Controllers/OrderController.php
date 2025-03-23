<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderController extends Controller
{
    protected $repo;

    public function __construct(OrderRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        $request->validate([
            'plants' => 'required|array|min:1',
            'plants.*.id' => 'required|exists:plants,id',
            'plants.*.quantity' => 'required|integer|min:1'
        ]);

        return response()->json($this->repo->store($request), 201);
    }

    public function myOrders()
    {
        return response()->json($this->repo->indexForClient());
    }

    public function cancel($id)
    {
        return $this->repo->cancel($id);
    }

    public function allOrders()
    {
        return response()->json($this->repo->indexForEmployee());
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:en_attente,en_preparation,livrée'
        ]);

        return response()->json($this->repo->updateStatus($id, $request->status));
    }
}

