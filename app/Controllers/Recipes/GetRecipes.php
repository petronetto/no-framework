<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use HelloFresh\Controllers\Controller;
use HelloFresh\Services\RecipeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetRecipes extends Controller
{
    /** @var \HelloFresh\Services\ServiceInterface */
    private $service;

    /**
     * Inject the service class dependency.
     *
     * @param RecipeService $service
     */
    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the pagineted Recipes.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $recipes = $this->service->paginate(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($recipes, 200);
    }
}
