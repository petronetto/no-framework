<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetRecipes extends RecipesBaseController
{
    /**
     * Get the pagineted Recipes.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $recipes = $this->service->paginate(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($recipes, 206);
    }
}
