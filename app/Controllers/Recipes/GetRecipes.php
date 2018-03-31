<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetRecipes extends RecipesBaseController
{
    /**
     * Get the pagineted Recipes.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $recipes = $this->service->paginate(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($recipes, 206);
    }
}
