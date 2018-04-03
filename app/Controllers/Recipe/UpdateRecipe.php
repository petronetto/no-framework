<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipe;

use HelloFresh\Models\Recipe;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

/**
 * Update one recipe
 *
 * @SWG\Put(
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
 *         response=200,
 *         description="The updated recipe",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/RecipeApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 * )
 *
 * @SWG\Patch(
 *     path="/recipes/{id}",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     security={
 *         {"Authorization": {}}
 *     },
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Recipe id",
 *         required=true,
 *         type="string"
 *     ),
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
 *         response=200,
 *         description="The updated recipe",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/RecipeApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 *     @SWG\Response(response=404, ref="#/definitions/Error"),
 * )
 */
class UpdateRecipe extends RecipesBaseController
{
    /**
     * @param  ServerRequestInterface $request
     * @param  int                    $id
     * @return ResponseInterface
     * @throws ValidationException
     */
    public function __invoke(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $data = $this->parseRequestDataToArray($request);

        $request->getMethod() === 'PATCH'
            ? $required = false
            : $required = true;

        $validation = v::key('name', v::stringType()->notEmpty()->length(5, 30), $required)
            ->key('description', v::stringType()->notEmpty()->length(5, null), $required)
            ->key('prep_time', v::intVal(), $required)
            ->key('difficulty', v::intVal()->between(1, 3), $required)
            ->key('vegetarian', v::boolType(), $required);

        $validation->assert($data);

        $recipe = $this->service->update($data, $id);

        return $this->json($recipe, 200);
    }
}
