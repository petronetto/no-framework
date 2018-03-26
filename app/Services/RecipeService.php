<?php

declare(strict_types=1);

namespace HelloFresh\Services;

use HelloFresh\Models\Recipe;
use HelloFresh\Transformers\RecipeTransformer;
use Petronetto\Cache\CacheManager as Cache;
use Petronetto\Http\Paginator;

class RecipeService extends AbstractService
{
    /** @var ORMIterface */
    private $model;

    /** @var Cache */
    private $cache;

    /** @var Paginator */
    private $paginator;

    /**
     * Get the model.
     *
     * @param Recipe    $model
     * @param Paginator $paginator
     */
    public function __construct(Recipe $model, Cache $cache, Paginator $paginator)
    {
        $this->model     = $model;
        $this->cache     = $cache;
        $this->paginator = $paginator;
    }

    /**
     * Paginate result.
     *
     * @param  int   $currentPage
     * @param  int   $perPage
     * @return array
     */
    public function paginate(int $currentPage, int $perPage): array
    {
        $query = $this->model->query();
        $total = $query->count();
        $query->skip(($currentPage - 1) * $perPage);
        $query->take($perPage);
        $data = $query->get()->toArray();

        $recipes = $this->paginator->paginate(
            $data,
            $total,
            $perPage,
            $currentPage,
            new RecipeTransformer()
        );

        $cacheKey = md5(serialize($recipes));

        if ($cached = $this->cache->get($cacheKey)) {
            $recipes = unserialize($cached);

            return $recipes;
        }

        $this->cache->set($cacheKey, serialize($recipes));

        return $recipes;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        //
    }

    public function delete($id)
    {
        //
    }

    public function getById($id)
    {
        //
    }

    public function update($id, array $data)
    {
        //
    }

    public function query()
    {
        return $this->model->query();
    }
}
