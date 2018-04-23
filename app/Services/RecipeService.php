<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Rating;
use App\Models\Recipe;
use App\Transformers\RecipeTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Item;
use Petronetto\Exceptions\NotFoundException;
use Petronetto\Exceptions\UnexpectedException;
use Petronetto\Http\Paginator;
use Petronetto\ORM\ORMInterface;

class RecipeService
{
    /** @var ORMIterface */
    private $recipe;

    /** @var ORMIterface */
    private $rating;

    /** @var CacheService */
    private $cache;

    /** @var Paginator */
    private $paginator;

    /**
     * @param Recipe       $recipe
     * @param CacheService $cache
     * @param Paginator    $paginator
     */
    public function __construct(Recipe $recipe, Rating $rating, CacheService $cache, Paginator $paginator)
    {
        $this->recipe     = $recipe;
        $this->rating     = $rating;
        $this->cache      = $cache;
        $this->paginator  = $paginator;
    }

    /**
     * @param  array               $data
     * @return array
     * @throws UnexpectedException
     */
    public function create(array $data): array
    {
        $recipe = (new $this->recipe())->fill($data);

        if ($recipe->save()) {
            // After save our recipe, we check if
            // have some cached key, and delete it
            $this->cache->delKeys('recipes_*');

            $recipe = $recipe->fresh();

            return $this->toResource($recipe);
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

        $total = $this->recipe->count();
        $data  = $this->recipe->skip(($currentPage - 1) * $perPage)
                    ->take($perPage)
                    ->orderBy('id', 'DESC')
                    ->get();

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
     * @param  string  $search
     * @param  integer $currentPage
     * @param  integer $perPage
     * @return array
     */
    public function search(string $search, int $currentPage, int $perPage): array
    {
        $cacheKey = "recipes_search_{$search}_page_{$currentPage}_per_page_{$perPage}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $result = $this->recipe->search($search);
        $total  = $result->count();

        $data = $result->skip(($currentPage - 1) * $perPage)
            ->take($perPage)
            ->get();

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
     * @param  integer           $id
     * @throws NotFoundException
     * @return array
     */
    public function getById(int $id): array
    {
        $cacheKey = "recipe_{$id}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $recipe = $this->recipe->find($id);

        // 404 - Not Found
        if (!$recipe) {
            throw new NotFoundException('Recipe not found');
        }

        $recipe = $this->toResource($recipe);

        $this->cache->set($cacheKey, $recipe);

        return $recipe;
    }

    /**
     * @param  array               $data
     * @param  int                 $id
     * @return array
     * @throws NotFoundException
     * @throws UnexpectedException
     */
    public function update(array $data, int $id): array
    {
        $recipe = $this->recipe->find($id);

        if (!$recipe) {
            throw new NotFoundException('Recipe not found');
        }

        $recipe->fill($data);

        if ($recipe->save()) {
            // Cleaning cache
            $this->cache->delKeys('recipes_*');

            $recipe = $recipe->fresh();

            return $this->toResource($recipe);
        }

        throw new UnexpectedException();
    }

    /**
     * @param  integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $recipe = $this->recipe->find($id);

        if (!$recipe) {
            throw new NotFoundException('Recipe not found');
        }

        if ($recipe->delete()) {
            $this->cache->delKeys('recipes_*');

            return true;
        }

        return false;
    }

    /**
     * @param  integer $id
     * @param  integer $rating
     * @return array
     */
    public function rating(int $id, int $rating): array
    {
        $recipe = $this->recipe->find($id);

        if (!$recipe) {
            throw new NotFoundException('Recipe not found');
        }

        $rating = $this->rating->fill(['rating' => $rating]);

        if ($recipe->ratings()->save($rating)) {
            $this->cache->delKeys('recipes_*');

            $recipe = $recipe->fresh();

            return $this->toResource($recipe);
        }

        throw new UnexpectedException();
    }

    /**
     * @param  array   $data
     * @param  integer $total
     * @param  integer $perPage
     * @param  integer $currentPage
     * @return array
     */
    private function paginate(Collection $data, int $total, int $perPage, int $currentPage): array
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
     * @param  array $recipe
     * @return array
     */
    private function toResource(ORMInterface $recipe): array
    {
        $item = new Item($recipe, new RecipeTransformer());

        return (new Fractal())
            ->createData($item)
            ->toArray();
    }
}
