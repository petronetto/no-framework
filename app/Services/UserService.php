<?php

declare(strict_types=1);

namespace HelloFresh\Services;

use HelloFresh\Models\User;
use HelloFresh\Transformers\UserTransformer;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Item;
use Petronetto\Exceptions\NotFoundHttpException;
use Petronetto\Http\Paginator;
use Petronetto\Exceptions\UnexpectedException;

class UserService
{
    /** @var ORMIterface */
    private $model;

    /**
     * @param User $model
     * @param CacheService $cache
     * @param Paginator $paginator
     */
    public function __construct(User $model, CacheService $cache, Paginator $paginator)
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
        $user = (new User())->fill($data);

        if ($user->save()) {
            // After save our user, we check if
            // have some cached key, and delete it
            $this->cache->delKeys('users_*');

            $user = $user->fresh();

            return $this->toResource($user->toArray());
        }

        // If the code reaches this point
        // it means that something went
        // wrong, so we throw an exception
        throw new UnexpectedException();
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
        $cacheKey = "users_page_{$currentPage}_per_page_{$perPage}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $query = $this->model->query();
        $total = $query->count();
        $query->skip(($currentPage - 1) * $perPage);
        $query->take($perPage);
        $data = $query->orderBy('id', 'DESC')->get()->toArray();

        $users = $this->paginator->paginate(
            $data,
            $total,
            $perPage,
            $currentPage,
            new UserTransformer()
        );

        $this->cache->set($cacheKey, $users);

        return $users;
    }

    /**
     * @param  integer $id
     * @throws NotFoundHttpException
     * @return array
     */
    public function getById(int $id): array
    {
        $cacheKey = "user_{$id}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $user = $this->model->find($id);

        // 404 - Not Found
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $user = $this->toResource($user->toArray());

        $this->cache->set($cacheKey, $user);

        return $user;
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
        $user = $this->model->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $user->fill($data);

        if ($user->save()) {
            // Cleaning cache
            $this->cache->delKeys('users_*');

            $user = $user->fresh();

            return $this->toResource($user->toArray());
        }

        throw new UnexpectedException();
    }

    /**
     * @param  integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $user = $this->model->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        if ($user->delete()) {
            $this->cache->delKeys('users_*');

            return true;
        }

        return false;
    }

    /**
     * @param  array $user
     * @return array
     */
    private function toResource(array $user): array
    {
        $item = new Item($user, new UserTransformer());

        return (new Fractal())
            ->createData($item)
            ->toArray();
    }
}
