<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Get the recipes paginated
 *
 * @SWG\Get(
 *     path="/recipes",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page",
 *         required=false,
 *         type="integer"
 *     ),
 *     @SWG\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Page size",
 *         required=false,
 *         type="integer"
 *     ),
 *     @SWG\Response(
 *         response=206,
 *         description="A paginated array of Recipes",
 *         @SWG\Schema(
 *             @SWG\Property(property="data", type="array", @SWG\Items(ref="#/definitions/RecipeApiResponse")),
 *             @SWG\Property(property="meta", type="object", ref="#/definitions/Meta"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 * )
 */
class GetRecipes extends RecipesBaseController
{
    /**
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $recipes = $this->service->paginate(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($recipes, 206);
    }
}
