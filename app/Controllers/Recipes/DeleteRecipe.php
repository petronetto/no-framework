<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Recipes;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Delete one recipe
 *
 * @SWG\Delete(
 *     path="/recipes/{id}",
 *     consumes={"application/json"},
 *     tags={"recipes"},
 *     @SWG\Response(
 *         response=204,
 *         description="An empty response",
 *     ),
 *     @SWG\Response(response=404, description="Recipe not found")
 * )
 */
class DeleteRecipe extends RecipesBaseController
{
    /**
     * @param  int               $id
     * @return ResponseInterface
     */
    public function __invoke(int $id): ResponseInterface
    {
        $this->service->delete($id);

        return new EmptyResponse();
    }
}
