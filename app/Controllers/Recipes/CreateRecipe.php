<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HelloFresh\Models\Recipe;

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
        $data = $this->parseRequestDataToArray($request);

        $recipe = $this->service->create($data);

        return $this->json($recipe, 201);
    }
}
