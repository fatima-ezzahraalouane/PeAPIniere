<?php

namespace App\Repositories\Interfaces;

interface StatisticsRepositoryInterface
{
    public function totalOrders();
    public function totalRevenue();
    public function topPlants();
    public function salesByCategory();
}
