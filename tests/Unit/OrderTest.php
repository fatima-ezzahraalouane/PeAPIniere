<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Plant;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_order_successfully()
    {
        // Fake Auth
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Simuler les données du client
        $plants = [
            ['id' => 1, 'quantity' => 2],
            ['id' => 2, 'quantity' => 1],
        ];

        $request = new Request(['plants' => $plants]);

        // Créer un mock d'Order avec méthode attach et load
        $mockOrder = Mockery::mock(Order::class)->makePartial();
        $mockOrder->shouldReceive('plants')->andReturnSelf();
        $mockOrder->shouldReceive('attach')->twice();
        $mockOrder->shouldReceive('load')->with('plants')->andReturn($mockOrder);

        // Mocker Order::create
        $orderClassMock = Mockery::mock('alias:App\Models\Order');
        $orderClassMock->shouldReceive('create')
            ->once()
            ->with(['user_id' => 1])
            ->andReturn($mockOrder);

        // Appel réel au repository
        $repo = new OrderRepository();
        $result = $repo->store($request);

        $this->assertEquals($mockOrder, $result);
    }
}
