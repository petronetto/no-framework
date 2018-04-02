<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Petronetto\Exceptions\NotFoundHttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Get one recipes by id
 *
 * @SWG\Get(
 *     path="/recipes/{id}",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Response(
 *         response=200,
 *         description="The recieved recipe",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/RecipeApiResponse"),
 *         ),
 *     ),
 *     @SWG\Response(response=404, description="Recipe not found")
 * )
 */
class GetRecipe extends RecipesBaseController
{
    /**
     * Get One Recipe by Id
     *
     * @param  int               $id
     * @return ResponseInterface
     */
    public function __invoke(int $id): ResponseInterface
    {
        $recipe = $this->service->getById($id);

        return $this->json($recipe, 200);
    }
}
