<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateRecipe extends RecipesBaseController
{
    /**
     * Create a new Recipe
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents());
        // FIXME: Remove
        dd($data->name);
        $recipes = $this->service->create($request);

        return $this->json($recipes, 206);
    }
}
