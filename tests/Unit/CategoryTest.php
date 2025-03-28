<?php

namespace Tests\Unit\Repositories;

use Mockery;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryRepository;
    protected $requestMock;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->requestMock = Mockery::mock(Request::class);
        $this->categoryRepository = new CategoryRepository();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndexReturnsAllCategories()
    {
        // Create actual Category records in the database
        $categories = Category::factory()->count(2)->create();

        $result = $this->categoryRepository->index();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function testStoreCreatesNewCategory()
    {
        $categoryData = [
            'name' => 'New Category'
        ];

        $this->requestMock
            ->shouldReceive('input')
            ->with('name')
            ->andReturn($categoryData['name']);

        $result = $this->categoryRepository->store($this->requestMock);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($categoryData['name'], $result->name);
        $this->assertDatabaseHas('categories', [
            'name' => $categoryData['name']
        ]);
    }

    public function testShowReturnsCategoryBySlug()
    {
        // Create an actual Category in the database
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $result = $this->categoryRepository->show('test-category');

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($category->slug, $result->slug);
        $this->assertEquals($category->name, $result->name);
    }

    public function testUpdateModifiesExistingCategory()
    {
        // Create an existing category
        $existingCategory = Category::factory()->create([
            'name' => 'Original Category',
            'slug' => 'original-category'
        ]);

        $newName = 'Updated Category';

        $this->requestMock
            ->shouldReceive('input')
            ->with('name')
            ->andReturn($newName);

        $result = $this->categoryRepository->update($this->requestMock, 'original-category');

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($newName, $result->name);
        $this->assertDatabaseHas('categories', [
            'name' => $newName,
            'slug' => 'original-category'
        ]);
    }

    public function testDestroyDeletesCategory()
    {
        // Create an existing category
        $category = Category::factory()->create([
            'name' => 'Category to Delete',
            'slug' => 'category-to-delete'
        ]);

        $response = $this->categoryRepository->destroy('category-to-delete');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Catégorie supprimée avec succès.', $responseData['message']);
        $this->assertDatabaseMissing('categories', [
            'slug' => 'category-to-delete'
        ]);
    }

    public function testShowThrowsExceptionForNonExistentSlug()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->categoryRepository->show('non-existent-slug');
    }
}