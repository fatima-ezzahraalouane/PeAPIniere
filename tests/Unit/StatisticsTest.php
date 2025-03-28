<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\DB;
use App\Repositories\StatisticsRepository;

class StatisticsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_total_orders()
    {
        DB::shouldReceive('table')
            ->once()
            ->with('orders')
            ->andReturnSelf();

        DB::shouldReceive('count')
            ->once()
            ->andReturn(10);

        $repo = new StatisticsRepository();
        $this->assertEquals(10, $repo->totalOrders());
    }

    public function test_total_revenue()
    {
        $expressionMock = Mockery::mock(\Illuminate\Database\Query\Expression::class);

        DB::shouldReceive('raw')
            ->once()
            ->with('SUM(order_plant.quantity * plants.price) as total')
            ->andReturn($expressionMock);

        DB::shouldReceive('table')
            ->once()
            ->with('order_plant')
            ->andReturnSelf();

        DB::shouldReceive('join')
            ->once()
            ->with('plants', 'order_plant.plant_id', '=', 'plants.id')
            ->andReturnSelf();

        DB::shouldReceive('select')
            ->once()
            ->with($expressionMock)
            ->andReturnSelf();

        DB::shouldReceive('value')
            ->once()
            ->with('total')
            ->andReturn(200);

        $repo = new StatisticsRepository();
        $this->assertEquals(200, $repo->totalRevenue());
    }

    public function test_top_plants()
    {
        $expression = Mockery::mock(\Illuminate\Database\Query\Expression::class);

        DB::shouldReceive('raw')
            ->once()
            ->with('SUM(order_plant.quantity) as total_quantity')
            ->andReturn($expression);

        DB::shouldReceive('table')
            ->once()
            ->with('order_plant')
            ->andReturnSelf();

        DB::shouldReceive('join')
            ->once()
            ->with('plants', 'order_plant.plant_id', '=', 'plants.id')
            ->andReturnSelf();

        DB::shouldReceive('select')
            ->once()
            ->with('plants.name', $expression)
            ->andReturnSelf();

        DB::shouldReceive('groupBy')
            ->once()
            ->with('plants.name')
            ->andReturnSelf();

        DB::shouldReceive('orderByDesc')
            ->once()
            ->with('total_quantity')
            ->andReturnSelf();

        DB::shouldReceive('limit')
            ->once()
            ->with(5)
            ->andReturnSelf();

        DB::shouldReceive('get')
            ->once()
            ->andReturn(collect([
                ['name' => 'Lavande', 'total_quantity' => 12]
            ]));

        $repo = new StatisticsRepository();
        $result = $repo->topPlants();

        $this->assertNotEmpty($result);
    }


    public function test_sales_by_category()
    {
        $expression = Mockery::mock(\Illuminate\Database\Query\Expression::class);

        DB::shouldReceive('raw')
            ->once()
            ->with('SUM(order_plant.quantity * plants.price) as total_sales')
            ->andReturn($expression);

        DB::shouldReceive('table')
            ->once()
            ->with('order_plant')
            ->andReturnSelf();

        DB::shouldReceive('join')
            ->once()
            ->with('plants', 'order_plant.plant_id', '=', 'plants.id')
            ->andReturnSelf();

        DB::shouldReceive('join')
            ->once()
            ->with('categories', 'plants.category_id', '=', 'categories.id')
            ->andReturnSelf();

        DB::shouldReceive('select')
            ->once()
            ->with('categories.name as category', $expression)
            ->andReturnSelf();

        DB::shouldReceive('groupBy')
            ->once()
            ->with('categories.name')
            ->andReturnSelf();

        DB::shouldReceive('get')
            ->once()
            ->andReturn(collect([
                ['category' => 'Plantes Médicinales', 'total_sales' => 150.00]
            ]));

        $repo = new StatisticsRepository();
        $result = $repo->salesByCategory();

        $this->assertNotEmpty($result);
    }
}
