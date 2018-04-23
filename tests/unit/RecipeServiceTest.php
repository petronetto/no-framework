<?php

declare(strict_types=1);

use App\Models\Rating;
use App\Models\Recipe;
use App\Services\CacheService;
use App\Services\RecipeService;
use Petronetto\Http\Paginator;
use Zend\Diactoros\ServerRequestFactory;

class RecipeServiceTest extends BaseTest
{
    /**
     * @covers \App\Services\RecipeService::get
     * @uses   \App\Services\RecipeService
     */
    public function test_get_recipes_paginated()
    {
        $service = $this->serviceFactory();

        $data = $service->get(1, 10);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('meta', $data);
        $this->assertEquals($data['meta']['pagination']['per_page'], 10);
    }

    /**
     * @covers \App\Services\RecipeService::create
     * @uses   \App\Services\RecipeService
     */
    public function test_create_recipe()
    {
        $service = $this->serviceFactory();

        $recipe = [
            'name'        => 'My fabulous recipe',
            'description' => 'A really long description for this fantastic recipe',
            'difficulty'  => 2,
            'prep_time'   => 60,
            'vegetarian'  => true
        ];

        $data = $service->create($recipe);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('meta', $data);
        $this->assertArrayHasKey('id', $data['data']);
        $this->assertArrayHasKey('average_rating', $data['data']);
        $this->assertArrayHasKey('created_at', $data['data']);
        $this->assertArrayHasKey('updated_at', $data['data']);

        $this->assertEquals($recipe['name'], $data['data']['name']);
        $this->assertEquals($recipe['description'], $data['data']['description']);
        $this->assertEquals($recipe['difficulty'], $data['data']['difficulty']);
        $this->assertEquals($recipe['prep_time'], $data['data']['prep_time']);
        $this->assertEquals($recipe['vegetarian'], $data['data']['vegetarian']);
        $this->assertEquals(0, $data['data']['average_rating']);
    }

    /**
     * @covers \App\Services\RecipeService::update
     * @uses   \App\Services\RecipeService
     */
    public function test_update_recipe()
    {
        $service = $this->serviceFactory();

        $recipe = Recipe::first();

        $newRecipeData = [
            'name'        => 'My fabulous recipe',
            'description' => 'A really long description for this fantastic recipe',
            'difficulty'  => 2,
            'prep_time'   => 60,
            'vegetarian'  => true
        ];

        $data = $service->update($newRecipeData, $recipe->id);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('meta', $data);

        // Checking the JSON response
        $this->assertEquals($recipe->id, $data['data']['id']);
        $this->assertEquals($newRecipeData['name'], $data['data']['name']);
        $this->assertEquals($newRecipeData['description'], $data['data']['description']);
        $this->assertEquals($newRecipeData['difficulty'], $data['data']['difficulty']);
        $this->assertEquals($newRecipeData['prep_time'], $data['data']['prep_time']);
        $this->assertEquals($newRecipeData['vegetarian'], $data['data']['vegetarian']);

        // Checking the database
        $recipe = $recipe->fresh();
        $this->assertEquals($newRecipeData['name'], $recipe->name);
        $this->assertEquals($newRecipeData['description'], $recipe->description);
        $this->assertEquals($newRecipeData['difficulty'], $recipe->difficulty);
        $this->assertEquals($newRecipeData['prep_time'], $recipe->prep_time);
        $this->assertEquals($newRecipeData['vegetarian'], $recipe->vegetarian);
    }

    /**
     * @covers \App\Services\RecipeService::getById
     * @uses   \App\Services\RecipeService
     */
    public function test_get_recipe_by_id()
    {
        $recipe = [
            'name'        => 'My fabulous recipe',
            'description' => 'A really long description for this fantastic recipe',
            'difficulty'  => 2,
            'prep_time'   => 60,
            'vegetarian'  => true
        ];

        $service = $this->serviceFactory();

        $recipe = $service->create($recipe);

        $data = $service->getById($recipe['data']['id']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('meta', $data);
        $this->assertEquals($data['data']['id'], $recipe['data']['id']);
        $this->assertEquals($data['data']['name'], $recipe['data']['name']);
        $this->assertEquals($data['data']['description'], $recipe['data']['description']);
    }

    /**
     * @covers \App\Services\RecipeService::delete
     * @uses   \App\Services\RecipeService
     */
    public function test_delete_recipe()
    {
        $service = $this->serviceFactory();

        $recipe = Recipe::first();

        $data = $service->delete($recipe->id);
        $this->assertEquals(true, $data);

        $recipe = Recipe::find($recipe->id);
        $this->assertEquals(null, $recipe);
    }

    /**
     * Factory for service.
     *
     * @return RecipeService
     */
    public function serviceFactory(): RecipeService
    {
        $cache = Mockery::mock(CacheService::class);
        $cache->shouldReceive([
            'get'     => false,
            'set'     => true,
            'delKeys' => true,
        ]);

        return new RecipeService(
            (new Recipe()),
            (new Rating()),
            $cache,
            (new Paginator(ServerRequestFactory::fromGlobals()))
        );
    }
}
