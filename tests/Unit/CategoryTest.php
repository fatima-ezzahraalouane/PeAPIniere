<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\CategoryRepository;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CategoryRepository();
    }

    public function test_index_returns_all_categories()
    {
        Category::factory()->count(3)->create();

        $categories = $this->repo->index();

        $this->assertCount(3, $categories);
    }

    public function test_store_creates_new_category()
    {
        $request = new Request(['name' => 'Fleurs']);

        $category = $this->repo->store($request);

        $this->assertEquals('Fleurs', $category->name);
        $this->assertDatabaseHas('categories', ['name' => 'Fleurs']);
    }

    public function test_show_returns_category_by_slug()
    {
        $category = Category::factory()->create(['name' => 'Fruits']);
        $found = $this->repo->show($category->slug);

        $this->assertEquals($category->id, $found->id);
    }

    public function test_update_modifies_category_name()
    {
        $category = Category::factory()->create(['name' => 'Légumes']);
        $request = new Request(['name' => 'Plantes']);

        $updated = $this->repo->update($request, $category->slug);

        $this->assertEquals('Plantes', $updated->name);
        $this->assertDatabaseHas('categories', ['name' => 'Plantes']);
    }

    public function test_destroy_deletes_category()
    {
        $category = Category::factory()->create();
        $response = $this->repo->destroy($category->slug);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertEquals('Catégorie supprimée avec succès.', $response->getData()->message);
    }
}
