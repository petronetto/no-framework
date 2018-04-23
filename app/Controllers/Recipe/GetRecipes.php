<?php

declare(strict_types=1);

namespace App\Controllers\Recipe;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Get the recipes paginated or search for a recipe
 *
 * @SWG\Get(
 *     path="/recipes",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Parameter(
 *         name="q",
 *         in="query",
 *         description="A term for a full text search",
 *         required=false,
 *         type="string"
 *     ),
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
 *         @SWG\Header(header="X-Powered-By", type="string", description="A name of a dev guy ;)"),
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
        $queryParams = $request->getQueryParams();
        if (array_key_exists('q', $queryParams)) {
            $recipes = $this->service->search(
                $queryParams['q'],
                $this->getCurrentPage($request),
                $this->getPageSize($request)
            );

            return $this->json($recipes, 206);
        }

        $recipes = $this->service->get(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($recipes, 206);
    }
}
