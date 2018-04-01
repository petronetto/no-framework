<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    /**
     * Get All Articles paginated
     *
     * @SWG\Get(
     *     path="/recipes",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"recipes"},
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="The result page",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="The page size",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=206,
     *         description="A paginated array of Articles",
     *         @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Recipe")),
     *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
     *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
     *     ),
     * )
     */
class GetRecipes extends RecipesBaseController
{
    /**
     * Get the pagineted Recipes.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $recipes = $this->service->paginate(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($recipes, 206);
    }
}
