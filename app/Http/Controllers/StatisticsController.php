<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\StatisticsRepositoryInterface;

class StatisticsController extends Controller
{
    protected $repo;

    public function __construct(StatisticsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function totalOrders()
    {
        $total = $this->repo->totalOrders();

        if ($total === 0) {
            return response()->json([
                'message' => 'Aucune commande trouvée.'
            ], 404);
        }

        return response()->json([
            'total_orders' => $total
        ], 200);
    }

    public function totalRevenue()
    {
        $total = $this->repo->totalRevenue();

        if (!$total) {
            return response()->json([
                'message' => 'Aucune vente enregistrée.'
            ], 404);
        }

        return response()->json([
            'total_revenue' => $total
        ], 200);
    }

    public function topPlants()
    {
        $plants = $this->repo->topPlants();

        if ($plants->isEmpty()) {
            return response()->json([
                'message' => 'Aucune plante commandée.'
            ], 404);
        }

        return response()->json([
            'top_plants' => $plants
        ], 200);
    }

    public function salesByCategory()
    {
        $categories = $this->repo->salesByCategory();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'Aucune vente enregistrée par catégorie.'
            ], 404);
        }

        return response()->json([
            'sales_by_category' => $categories
        ], 200);
    }
}