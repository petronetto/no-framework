<?php

declare(strict_types=1);

namespace HelloFresh\Services;

use HelloFresh\Models\Recipe;
use HelloFresh\Transformers\RecipeTransformer;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Item;
use Petronetto\Exceptions\NotFoundHttpException;
use Petronetto\Http\Paginator;
use Petronetto\Exceptions\UnexpectedException;

class RecipeService
{
    /** @var ORMIterface */
    private $model;

    /** @var CacheService */
    private $cache;

    /** @var Paginator */
    private $paginator;

    /**
     * @param Recipe $model
     * @param CacheService $cache
     * @param Paginator $paginator
     */
    public function __construct(Recipe $model, CacheService $cache, Paginator $paginator)
    {
        $this->model     = $model;
        $this->cache     = $cache;
        $this->paginator = $paginator;
    }

    /**
     * @param  array $data
     * @return array
     * @throws UnexpectedException
     */
    public function create(array $data): array
    {
        $recipe = (new Recipe())->fill($data);

        if ($recipe->save()) {
            // After save our recipe, we check if
            // have some cached key, and delete it
            $this->cache->delKeys('recipes_*');

            $recipe = $recipe->fresh();

            return $this->toResource($recipe->toArray());
        }

        // If the code reaches this point
        // it means that something went
        // wrong, so we throw an exception
        throw new UnexpectedException();
    }

    /**
     * @param  int   $currentPage
     * @param  int   $perPage
     * @return array
     */
    public function get(int $currentPage, int $perPage): array
    {
        $cacheKey = "recipes_page_{$currentPage}_per_page_{$perPage}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $query = $this->model->query();
        $total = $query->count();
        $query->skip(($currentPage - 1) * $perPage);
        $query->take($perPage);
        $data = $query->orderBy('id', 'DESC')->get()->toArray();

        $recipes = $this->paginate(
            $data,
            $total,
            $perPage,
            $currentPage
        );

        $this->cache->set($cacheKey, $recipes);

        return $recipes;
    }

    /**
     * @param string $search
     * @param integer $currentPage
     * @param integer $perPage
     * @return array
     */
    public function search(string $search, int $currentPage, int $perPage): array
    {
        $cacheKey = "recipes_search_{$search}_page_{$currentPage}_per_page_{$perPage}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $result = $this->model->search($search);
        $total = $result->count();

        $result->skip(($currentPage - 1) * $perPage);
        $result->take($perPage);
        $data = $result->get()->toArray();

        $recipes = $this->paginate(
            $data,
            $total,
            $perPage,
            $currentPage
        );

        $this->cache->set($cacheKey, $recipes);

        return $recipes;
    }

    /**
     * @param array $data
     * @param integer $total
     * @param integer $perPage
     * @param integer $currentPage
     * @return array
     */
    public function paginate(array $data, int $total, int $perPage, int $currentPage): array
    {
        $recipes = $this->paginator->paginate(
            $data,
            $total,
            $perPage,
            $currentPage,
            new RecipeTransformer()
        );

        return $recipes;
    }

    /**
     * @param  integer $id
     * @throws NotFoundHttpException
     * @return array
     */
    public function getById(int $id): array
    {
        $cacheKey = "recipe_{$id}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $recipe = $this->model->find($id);

        // 404 - Not Found
        if (!$recipe) {
            throw new NotFoundHttpException('Recipe not found');
        }

        $recipe = $this->toResource($recipe->toArray());

        $this->cache->set($cacheKey, $recipe);

        return $recipe;
    }

    /**
     * @param array $data
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @throws UnexpectedException
     */
    public function update(array $data, int $id): array
    {
        $recipe = $this->model->find($id);

        if (!$recipe) {
            throw new NotFoundHttpException('Recipe not found');
        }

        $recipe->fill($data);

        if ($recipe->save()) {
            // Cleaning cache
            $this->cache->delKeys('recipes_*');

            $recipe = $recipe->fresh();

            return $this->toResource($recipe->toArray());
        }

        throw new UnexpectedException();
    }

    /**
     * @param  integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $recipe = $this->model->find($id);

        if (!$recipe) {
            throw new NotFoundHttpException('Recipe not found');
        }

        if ($recipe->delete()) {
            $this->cache->delKeys('recipes_*');

            return true;
        }

        return false;
    }

    /**
     * @param  array $recipe
     * @return array
     */
    private function toResource(array $recipe): array
    {
        $item = new Item($recipe, new RecipeTransformer());

        return (new Fractal())
            ->createData($item)
            ->toArray();
    }
}
