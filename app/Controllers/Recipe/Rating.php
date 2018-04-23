<?php

declare(strict_types=1);

namespace App\Controllers\Recipe;

use App\Models\Recipe;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;

/**
 * Create a new rating for a recipe
 *
 * @SWG\Post(
 *     path="/recipes/{id}/rating",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Parameter(
 *         name="payload",
 *         in="body",
 *         description="Rating payload",
 *         required=true,
 *         @SWG\Schema(
 *             @SWG\Property(property="rating", type="integer", example=5),
 *         )
 *     ),
 *     @SWG\Response(
 *         response=201,
 *         description="",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/RecipeApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description="A name of a dev guy ;)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 * )
 */
class Rating extends RecipesBaseController
{
    /**
     * Create a new Recipe
     *
     * @param  ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $data = $this->parseRequestDataToArray($request);

        $validation = v::key('rating', v::intVal()->between(1, 5));

        $validation->assert($data);

        $recipe = $this->service->rating($id, $data['rating']);

        return $this->json($recipe, 201);
    }
}
