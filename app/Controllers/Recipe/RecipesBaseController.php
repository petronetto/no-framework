<?php

declare(strict_types=1);

namespace App\Controllers\Recipe;

use App\Controllers\Controller;
use App\Services\RecipeService;

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
