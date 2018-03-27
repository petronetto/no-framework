<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use HelloFresh\Controllers\Controller;
use HelloFresh\Services\RecipeService;

class RecipesBaseController extends Controller
{
    /** @var RecipeService */
    protected $service;

    /**
     * Inject the service in the base controller
     *
     * @param RecipeService $service
     */
    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }
}
