<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HelloFresh\Models\Recipe;
use Respect\Validation\Validator as v;

/**
 * Get the recipes paginated
 *
 * @SWG\Get(
 *     path="/recipes",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Parameter(
 *         name="payload",
 *         in="body",
 *         description="Card payload",
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
 *         @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Recipe")),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 * )
 */
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

        $userValidation = v::key('name', v::alpha()->length(5, 30))
            ->key('description', v::alpha())
            ->key('prep_time', v::intVal())
            ->key('difficulty', v::intVal()->between(1, 3))
            ->key('vegetarian', v::boolType());

        $userValidation->assert($data);

        $recipe = $this->service->create($data);

        return $this->json($recipe, 201);
    }
}
