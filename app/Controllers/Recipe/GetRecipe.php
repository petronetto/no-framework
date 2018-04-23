<?php

declare(strict_types=1);

namespace App\Controllers\Recipe;

use Psr\Http\Message\ResponseInterface;

/**
 * Get one recipes by id
 *
 * @SWG\Get(
 *     path="/recipes/{id}",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Recipe id",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="The recieved recipe",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/RecipeApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description="A name of a dev guy ;)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=404, ref="#/definitions/Error"),
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
