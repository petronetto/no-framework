<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Get the users paginated
 *
 * @SWG\Get(
 *     path="/users",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"users"},
 *     security={
 *         {"Authorization": {}}
 *     },
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
 *         description="A paginated array of Users",
 *         @SWG\Schema(
 *             @SWG\Property(property="data", type="array", @SWG\Items(ref="#/definitions/UserApiResponse")),
 *             @SWG\Property(property="meta", type="object", ref="#/definitions/Meta"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 * )
 */
class GetUsers extends UsersBaseController
{
    /**
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $users = $this->service->paginate(
            $this->getCurrentPage($request),
            $this->getPageSize($request)
        );

        return $this->json($users, 206);
    }
}
