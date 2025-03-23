<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => Auth::id()
        ]);

        foreach ($request->plants as $plant) {
            $order->plants()->attach($plant['id'], ['quantity' => $plant['quantity']]);
        }

        return $order->load('plants');
    }

    public function indexForClient()
    {
        return Order::with('plants')->where('user_id', Auth::id())->get();
    }

    public function indexForEmployee()
    {
        return Order::with('user', 'plants')->get();
    }

    public function show($id)
    {
        return Order::with('plants')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    }

    public function cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status !== 'en_attente') {
            return response()->json(['error' => 'Commande déjà en cours de traitement.'], 403);
        }

        $order->delete();

        return response()->json(['message' => 'Commande annulée avec succès.']);
    }

    public function updateStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);

        return $order->load('user', 'plants');
    }
}
