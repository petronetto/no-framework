<?php

declare(strict_types=1);

namespace App\Controllers\Recipe;

use App\Models\Recipe;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;

/**
 * Create a new recipe
 *
 * @SWG\Post(
 *     path="/recipes",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     security={
 *         {"Authorization": {}}
 *     },
 *     @SWG\Parameter(
 *         name="payload",
 *         in="body",
 *         description="Recipe payload",
 *         required=true,
 *         @SWG\Schema(
 *             @SWG\Property(property="name", type="string", example="Lorem ipsum"),
 *             @SWG\Property(property="description", type="string", example="Lorem ipsum dolar net est"),
 *             @SWG\Property(property="difficulty", type="integer", example=3),
 *             @SWG\Property(property="prep_time", type="integer", example=60),
 *             @SWG\Property(property="vegetarian", type="boolean", example=true),
 *         )
 *     ),
 *     @SWG\Response(
 *         response=201,
 *         description="The created recipe",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/RecipeApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description="A name of a dev guy ;)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 * )
 */
class CreateRecipe extends RecipesBaseController
{
    /**
     * Create a new Recipe
     *
     * @param  ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->parseRequestDataToArray($request);

        $validation = v::key('name', v::stringType()->notEmpty()->length(5, 30))
            ->key('description', v::stringType()->notEmpty()->length(5, null))
            ->key('prep_time', v::intVal())
            ->key('difficulty', v::intVal()->between(1, 3))
            ->key('vegetarian', v::boolType());

        $validation->assert($data);

        $recipe = $this->service->create($data);

        return $this->json($recipe, 201);
    }
}
