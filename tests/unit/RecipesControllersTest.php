<?php

declare(strict_types=1);

use HelloFresh\Controllers\Recipe\CreateRecipe;
use HelloFresh\Controllers\Recipe\GetRecipe;
use HelloFresh\Controllers\Recipe\GetRecipes;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class RecipesControllersTest extends BaseTest
{
    /**
     * @covers \HelloFresh\Controllers\Recipe\GetRecipes::__invoke
     * @uses   \HelloFresh\Controllers\Recipe\GetRecipes
     */
    public function test_get_recipes()
    {
        $getRecipes = container()->get(GetRecipes::class);
        $request    = container()->get('request');
        $res        = $getRecipes($request);

        $this->assertInstanceOf(JsonResponse::class, $res);
        $this->assertEquals($res->getStatusCode(), 206);
    }

    /**
     * @covers \HelloFresh\Controllers\Recipe\GetRecipe::__invoke
     * @uses   \HelloFresh\Controllers\Recipe\GetRecipe
     */
    public function test_get_recipe()
    {
        $getRecipe = container()->get(GetRecipe::class);
        $res       = $getRecipe(1);

        $this->assertInstanceOf(JsonResponse::class, $res);
        $this->assertEquals($res->getStatusCode(), 200);
    }

    /**
     * @covers \HelloFresh\Controllers\Recipe\CreateRecipe::__invoke
     * @uses   \HelloFresh\Controllers\Recipe\CreateRecipe
     */
    public function test_create_recipe()
    {
        $recipe = json_encode([
            'name'        => 'My fabulous recipe',
            'description' => 'A really long description for this fantastic recipe',
            'difficulty'  => 2,
            'prep_time'   => 60,
            'vegetarian'  => true
        ]);

        $getRecipe    = container()->get(CreateRecipe::class);
        $request      =  Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getBody')
            ->andReturn($request)
            ->shouldReceive('getContents')
            ->andReturn($recipe);

        $res = $getRecipe($request);

        $this->assertInstanceOf(JsonResponse::class, $res);
        $this->assertEquals($res->getStatusCode(), 201);
    }

    /**
     * @covers \HelloFresh\Controllers\Recipe\CreateRecipe::__invoke
     * @uses   \HelloFresh\Controllers\Recipe\CreateRecipe
     * @expectedException \Respect\Validation\Exceptions\AllOfException
     */
    public function test_create_recipe_validation()
    {
        $getRecipe = container()->get(CreateRecipe::class);
        $request   = container()->get('request');

        $res = $getRecipe($request);
    }
}
