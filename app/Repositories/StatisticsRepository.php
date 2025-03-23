<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\StatisticsRepositoryInterface;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    public function totalOrders()
    {
        return DB::table('orders')->count();
    }

    public function totalRevenue()
    {
        return DB::table('order_plant')
            ->join('plants', 'order_plant.plant_id', '=', 'plants.id')
            ->select(DB::raw('SUM(order_plant.quantity * plants.price) as total'))
            ->value('total');
    }

    public function topPlants()
    {
        return DB::table('order_plant')
            ->join('plants', 'order_plant.plant_id', '=', 'plants.id')
            ->select('plants.name', DB::raw('SUM(order_plant.quantity) as total_quantity'))
            ->groupBy('plants.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    public function salesByCategory()
    {
        return DB::table('order_plant')
            ->join('plants', 'order_plant.plant_id', '=', 'plants.id')
            ->join('categories', 'plants.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('SUM(order_plant.quantity * plants.price) as total_sales'))
            ->groupBy('categories.name')
            ->get();
    }
}
