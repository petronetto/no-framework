<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetRecipe extends RecipesBaseController
{
    /**
     * Get One Recipe by Id
     *
     * @param  int               $id
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        int $id
    ): ResponseInterface {
        $recipe = $this->service->getById($id);

        return $this->json($recipe, 200);
    }
}
