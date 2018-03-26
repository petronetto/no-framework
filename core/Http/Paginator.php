<?php

declare(strict_types=1);

namespace Petronetto\Http;

use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Psr\Http\Message\ServerRequestInterface;

class Paginator
{
    private $request;

    public function __construct(ServerRequestInterface $req)
    {
        $this->request = $req;
    }

    /**
     * Create a LengthAwarePaginator.
     *
     * @param  array                $data
     * @param  int                  $total
     * @param  int                  $perPage
     * @param  int                  $currentPage
     * @return LengthAwarePaginator
     */
    private function getPaginator(array $data, int $total, int $perPage, int $currentPage): LengthAwarePaginator
    {
        $queryString = $this->getQueryString();

        if (array_key_exists('page', $queryString)) {
            unset($queryString['page']);
        }

        $offset      = ($currentPage - 1) * $perPage;
        $options     = [
            'path'  => $this->request->getUri()->getPath(),
            'query' => $queryString,
        ];

        return new LengthAwarePaginator(
            $data,
            $total,
            $perPage,
            $currentPage,
            $options
        );
    }

    /**
     * Get the ressults paginated.
     *
     * @param  array               $data
     * @param  int                 $total
     * @param  int                 $perPage
     * @param  int                 $currentPage
     * @param  TransformerAbstract $transformer
     * @return array
     */
    public function paginate(
        array $data,
        int $total,
        int $perPage,
        int $currentPage,
        TransformerAbstract $transformer
    ): array {
        $paginator = $this->getPaginator($data, $total, $perPage, $currentPage);
        $data      = $paginator->getCollection();
        $resource  = new Collection($data, $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $fractal = new Manager();

        return $fractal->createData($resource)->toArray();
    }

    /**
     * Get the query string and serializes to array.
     *
     * @return array
     */
    private function getQueryString(): array
    {
        parse_str($this->request->getUri()->getQuery(), $queryString);

        if (isset($queryString['p'])) {
            unset($queryString['p']);
        }

        return $queryString;
    }
}
