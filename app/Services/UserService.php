<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Transformers\UserTransformer;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Item;
use Petronetto\Exceptions\NotFoundException;
use Petronetto\Exceptions\UnexpectedException;
use Petronetto\Http\Paginator;
use Petronetto\ORM\ORMInterface;

class UserService
{
    /** @var ORMIterface */
    private $user;

    /**
     * @param User         $user
     * @param CacheService $cache
     * @param Paginator    $paginator
     */
    public function __construct(User $user, CacheService $cache, Paginator $paginator)
    {
        $this->user      = $user;
        $this->cache     = $cache;
        $this->paginator = $paginator;
    }

    /**
     * @param  array               $data
     * @return array
     * @throws UnexpectedException
     */
    public function create(array $data): array
    {
        $user = (new $this->user())->fill($data);

        if ($user->save()) {
            // After save our user, we check if
            // have some cached key, and delete it
            $this->cache->delKeys('users_*');

            $user = $user->fresh();

            return $this->toResource($user);
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
    public function get(int $currentPage, int $perPage): array
    {
        $cacheKey = "users_page_{$currentPage}_per_page_{$perPage}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $total = $this->user->count();
        $data  = $this->user->skip(($currentPage - 1) * $perPage)
                    ->take($perPage)
                    ->orderBy('id', 'DESC')
                    ->get();

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
     * @param  integer           $id
     * @throws NotFoundException
     * @return array
     */
    public function getById(int $id): array
    {
        $cacheKey = "user_{$id}";

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $user = $this->user->find($id);

        // 404 - Not Found
        if (!$user) {
            throw new NotFoundException('User not found');
        }

        $user = $this->toResource($user);

        $this->cache->set($cacheKey, $user);

        return $user;
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
        $user = $this->user->find($id);

        if (!$user) {
            throw new NotFoundException('User not found');
        }

        $user->fill($data);

        if ($user->save()) {
            // Cleaning cache
            $this->cache->delKeys('users_*');

            $user = $user->fresh();

            return $this->toResource($user);
        }

        throw new UnexpectedException();
    }

    /**
     * @param  integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new NotFoundException('User not found');
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
    private function toResource(ORMInterface $user): array
    {
        $item = new Item($user, new UserTransformer());

        return (new Fractal())
            ->createData($item)
            ->toArray();
    }
}
