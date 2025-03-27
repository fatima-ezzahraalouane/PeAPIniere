<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Mockery;
use App\Models\Plant;
use App\Models\Image;
use App\Models\Category;
use App\Repositories\PlantRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class PlantTest extends TestCase
{
    private $plantRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->plantRepository = new PlantRepository();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_all_plants_with_relations()
    {
        // Arrange
        $expectedPlants = new Collection([
            Mockery::mock(Plant::class, ['id' => 1, 'name' => 'Test Plant'])
        ]);

        $query = Mockery::mock('query');
        $query->shouldReceive('with')
            ->with('category', 'images')
            ->once()
            ->andReturnSelf();
        
        $query->shouldReceive('get')
            ->once()
            ->andReturn($expectedPlants);

        Plant::shouldReceive('with')
            ->with('category', 'images')
            ->once()
            ->andReturn($query);

        // Act
        $result = $this->plantRepository->index();

        // Assert
        $this->assertEquals($expectedPlants, $result);
    }

    public function test_show_returns_plant_by_slug()
    {
        // Arrange
        $plant = Mockery::mock(Plant::class);
        $plant->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $plant->shouldReceive('getAttribute')->with('name')->andReturn('Test Plant');

        $query = Mockery::mock('query');
        $query->shouldReceive('with')
            ->with('category', 'images')
            ->once()
            ->andReturnSelf();

        $query->shouldReceive('where')
            ->with('slug', 'test-plant')
            ->once()
            ->andReturnSelf();

        $query->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($plant);

        Plant::shouldReceive('with')
            ->with('category', 'images')
            ->once()
            ->andReturn($query);

        // Act
        $result = $this->plantRepository->show('test-plant');

        // Assert
        $this->assertEquals($plant, $result);
    }

    public function test_store_creates_new_plant_with_images()
    {
        // Arrange
        $request = new Request([
            'name' => 'Test Plant',
            'description' => 'Test Description',
            'price' => 99.99,
            'category_id' => 1,
            'images' => ['image1.jpg', 'image2.jpg']
        ]);

        $plant = Mockery::mock(Plant::class);
        $plant->shouldReceive('getAttribute')->andReturn(1);
        
        $imagesRelation = Mockery::mock('images');
        $imagesRelation->shouldReceive('create')
            ->with(['url' => 'image1.jpg'])
            ->once();
        $imagesRelation->shouldReceive('create')
            ->with(['url' => 'image2.jpg'])
            ->once();

        $plant->shouldReceive('images')
            ->twice()
            ->andReturn($imagesRelation);

        $plant->shouldReceive('load')
            ->with('category', 'images')
            ->once()
            ->andReturnSelf();

        Plant::shouldReceive('create')
            ->with([
                'name' => 'Test Plant',
                'description' => 'Test Description',
                'price' => 99.99,
                'category_id' => 1
            ])
            ->once()
            ->andReturn($plant);

        // Act
        $result = $this->plantRepository->store($request);

        // Assert
        $this->assertEquals($plant, $result);
    }

    public function test_update_modifies_existing_plant()
    {
        // Arrange
        $request = new Request([
            'name' => 'Updated Plant',
            'description' => 'Updated Description',
            'price' => 149.99,
            'category_id' => 1,
            'images' => ['new-image.jpg']
        ]);

        $plant = Mockery::mock(Plant::class);
        
        $query = Mockery::mock('query');
        $query->shouldReceive('where')
            ->with('slug', 'test-plant')
            ->once()
            ->andReturnSelf();

        $query->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($plant);

        Plant::shouldReceive('where')
            ->with('slug', 'test-plant')
            ->once()
            ->andReturn($query);

        $plant->shouldReceive('update')
            ->with([
                'name' => 'Updated Plant',
                'description' => 'Updated Description',
                'price' => 149.99,
                'category_id' => 1,
            ])
            ->once();

        $imagesRelation = Mockery::mock('images');
        $imagesRelation->shouldReceive('create')
            ->with(['url' => 'new-image.jpg'])
            ->once();

        $plant->shouldReceive('images')
            ->once()
            ->andReturn($imagesRelation);

        $plant->shouldReceive('load')
            ->with('category', 'images')
            ->once()
            ->andReturnSelf();

        // Act
        $result = $this->plantRepository->update($request, 'test-plant');

        // Assert
        $this->assertEquals($plant, $result);
    }

    public function test_destroy_deletes_plant_and_related_images()
    {
        // Arrange
        $plant = Mockery::mock(Plant::class);
        
        $query = Mockery::mock('query');
        $query->shouldReceive('where')
            ->with('slug', 'test-plant')
            ->once()
            ->andReturnSelf();

        $query->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($plant);

        Plant::shouldReceive('where')
            ->with('slug', 'test-plant')
            ->once()
            ->andReturn($query);

        $imagesRelation = Mockery::mock('images');
        $imagesRelation->shouldReceive('delete')
            ->once();

        $plant->shouldReceive('images')
            ->once()
            ->andReturn($imagesRelation);

        $plant->shouldReceive('delete')
            ->once();

        // Act
        $result = $this->plantRepository->destroy('test-plant');

        // Assert
        $this->assertEquals('Plante supprimée avec succès', $result->getData()->message);
    }

    public function test_show_throws_exception_for_invalid_slug()
    {
        // Arrange
        $query = Mockery::mock('query');
        $query->shouldReceive('with')
            ->with('category', 'images')
            ->once()
            ->andReturnSelf();

        $query->shouldReceive('where')
            ->with('slug', 'invalid-slug')
            ->once()
            ->andReturnSelf();

        $query->shouldReceive('firstOrFail')
            ->once()
            ->andThrow(ModelNotFoundException::class);

        Plant::shouldReceive('with')
            ->with('category', 'images')
            ->once()
            ->andReturn($query);

        // Assert & Act
        $this->expectException(ModelNotFoundException::class);
        $this->plantRepository->show('invalid-slug');
    }
}