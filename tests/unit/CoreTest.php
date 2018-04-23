<?php

declare(strict_types=1);

use App\Models\Recipe;
use App\Transformers\RecipeTransformer;
use Petronetto\Http\Paginator;
use Zend\Diactoros\ServerRequestFactory;

class CoreTest extends BaseTest
{
    /**
     * @covers \Petronetto\Application::__construct
     * @uses   \Petronetto\Application
     */
    public function test_application_instance(): void
    {
        $app = new Petronetto\Application();
        $this->assertInstanceOf(
            \Petronetto\Application::class,
            $app
        );

        $this->assertInstanceOf(
            \DI\Container::class,
            $app->getContainer()
        );
    }

    /**
     * @covers \Petronetto\Routing\Router::__construct
     * @uses   \Petronetto\Routing\Router
     */
    public function test_router_instance(): void
    {
        $router = new \Petronetto\Routing\Router();
        $this->assertInstanceOf(
            \Petronetto\Routing\Router::class,
            $router
        );
    }

    /**
     * @covers \Petronetto\Config::getInstance
     * @uses   \Petronetto\Config
     */
    public function test_config_instance(): void
    {
        $config = \Petronetto\Config::getInstance();
        $this->assertInstanceOf(
            \Petronetto\Config::class,
            $config
        );
    }

    /**
     * @covers \Petronetto\Config::get
     * @covers \Petronetto\Config::loadData
     * @covers \Petronetto\Config::getPathAndFileName
     * @uses   \Petronetto\Config
     */
    public function test_config_get(): void
    {
        $config = \Petronetto\Config::getInstance();

        $cors = $config->get('cors');

        $corsCheck = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => '*',
            'Access-Control-Allow-Headers'     => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
        ];

        $this->assertEquals($cors, $corsCheck);
    }

    /**
     * @covers \Petronetto\Config::get
     * @covers \Petronetto\Config::loadData
     * @covers \Petronetto\Config::getPathAndFileName
     * @uses   \Petronetto\Config
     */
    public function test_config_get_fallback(): void
    {
        $config = \Petronetto\Config::getInstance();

        $test = $config->get('dontexists', 'fallback');

        $this->assertEquals($test, 'fallback');
    }

    /**
     * @covers \Petronetto\Http\Paginator::__construct
     * @covers \Petronetto\Http\Paginator::paginate
     * @uses   \Petronetto\Http\Paginator
     */
    public function test_paginator(): void
    {
        $recipes = Recipe::take(10)->get();

        $request = ServerRequestFactory::fromGlobals();

        $paginator = (new Paginator($request))->paginate(
            $recipes,
            10,
            10,
            1,
            new RecipeTransformer()
        );

        $this->assertEquals(is_array($paginator), true);
        $this->assertArrayHasKey('data', $paginator);
        $this->assertArrayHasKey('meta', $paginator);
    }
}
