<?php

declare(strict_types=1);

namespace HelloFresh\Services;

use HelloFresh\Models\Recipe;
use HelloFresh\Transformers\RecipeTransformer;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Item;
use Petronetto\Exceptions\NotFoundHttpException;
use Petronetto\Http\Paginator;

class RecipeService extends AbstractService
{
    /** @var ORMIterface */
    private $model;

    /** @var CacheService */
    private $cache;

    /** @var Paginator */
    private $paginator;

    /**
     * Get the model.
     *
     * @param Recipe    $model
     * @param Paginator $paginator
     */
    public function __construct(Recipe $model, CacheService $cache, Paginator $paginator)
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
        $cacheKey = "recipes_page_{$currentPage}_per_page_{$perPage}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

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

        $this->cache->set($cacheKey, $recipes);

        return $recipes;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Create a new Recipe
     *
     * @param array $data
     * @return array
     */
    public function create(array $data):array
    {
        $recipe = (new Recipe())->fill($data);

        if ($recipe->save()) {
            // After save our recipe, we check if
            // have some cached key, and delete it
            $keys = $this->cache->keys('recipes_*');
            if ($keys) {
                $this->cache->del($keys);
            }
            return (new Fractal())->createData(
                new Item(
                    $recipe->fresh()->toArray(),
                    new RecipeTransformer()
                )
            )->toArray();
        }
        // TODO: Improve it...
        throw new \Exception('Error Processing Request', 500);
    }

    public function delete($id)
    {
        //
    }

    public function getById(int $id): array
    {
        $cacheKey = "recipe_{$id}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $recipe = $this->model->find($id);

        // TODO: Improve it...
        if (!$recipe) {
            throw new NotFoundHttpException();
        }

        $recipe = (new Fractal())->createData(
            new Item(
                $recipe->toArray(),
                new RecipeTransformer()
            )
        )->toArray();

        $this->cache->set($cacheKey, $recipe);

        return $recipe;
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
