<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\CacheService;
use App\Services\UserService;
use Petronetto\Http\Paginator;
use Zend\Diactoros\ServerRequestFactory;

class UserServiceTest extends BaseTest
{
    /**
     * @covers \App\Services\UserService::get
     * @uses   \App\Services\UserService
     */
    public function test_get_users_paginated()
    {
        $service = $this->serviceFactory();

        $data = $service->get(1, 10);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('meta', $data);
        $this->assertEquals($data['meta']['pagination']['per_page'], 10);
    }

    /**
     * @covers \App\Services\UserService::getById
     * @uses   \App\Services\UserService
     */
    public function test_get_user_by_id()
    {
        $service = $this->serviceFactory();

        $user = $this->userFaker();

        $user = $service->create($this->userFaker());

        $data = $service->getById($user['data']['id']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('meta', $data);
        $this->assertEquals($data['data']['id'], $user['data']['id']);
        $this->assertEquals($data['data']['username'], $user['data']['username']);
        $this->assertEquals($data['data']['email'], $user['data']['email']);
    }

    /**
     * @covers \App\Services\UserService::create
     * @uses   \App\Services\UserService
     */
    public function test_create_user()
    {
        $service = $this->serviceFactory();

        $user = $this->userFaker();

        $data = $service->create($user);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('meta', $data);
        $this->assertArrayHasKey('id', $data['data']);
        $this->assertArrayNotHasKey('password', $data['data']);
        $this->assertArrayHasKey('created_at', $data['data']);
        $this->assertArrayHasKey('updated_at', $data['data']);

        $this->assertEquals($user['username'], $data['data']['username']);
        $this->assertEquals($user['email'], $data['data']['email']);
        $this->assertEquals($user['first_name'], $data['data']['first_name']);
        $this->assertEquals($user['last_name'], $data['data']['last_name']);
    }

    /**
     * @covers \App\Services\UserService::update
     * @uses   \App\Services\UserService
     */
    public function test_update_user()
    {
        $service = $this->serviceFactory();

        $user = User::first();

        $newUserData = $this->userFaker();

        $data = $service->update($newUserData, $user->id);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('meta', $data);

        // Checking the JSON response
        $this->assertEquals($user->id, $data['data']['id']);
        $this->assertEquals($newUserData['username'], $data['data']['username']);
        $this->assertEquals($newUserData['email'], $data['data']['email']);
        $this->assertEquals($newUserData['first_name'], $data['data']['first_name']);
        $this->assertEquals($newUserData['last_name'], $data['data']['last_name']);

        // Checking the database
        $user = $user->fresh();
        $this->assertEquals($newUserData['username'], $user->username);
        $this->assertEquals($newUserData['email'], $user->email);
        $this->assertEquals($newUserData['first_name'], $user->first_name);
        $this->assertEquals($newUserData['last_name'], $user->last_name);
    }

    /**
     * @covers \App\Services\UserService::delete
     * @uses   \App\Services\UserService
     */
    public function test_delete_user()
    {
        $service = $this->serviceFactory();

        $user = User::first();

        $data = $service->delete($user->id);
        $this->assertEquals(true, $data);

        $user = User::find($user->id);
        $this->assertEquals(null, $user);
    }

    /**
     * Factory for service.
     *
     * @return UserService
     */
    private function serviceFactory(): UserService
    {
        $cache = Mockery::mock(CacheService::class);
        $cache->shouldReceive([
            'get'     => false,
            'set'     => true,
            'delKeys' => true,
        ]);

        return new UserService(
            (new User()),
            $cache,
            (new Paginator(ServerRequestFactory::fromGlobals()))
        );
    }

    /**
     * @return array
     */
    private function userFaker(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'username'              => $faker->userName,
            'email'                 => $faker->email,
            'first_name'            => $faker->firstName,
            'last_name'             => $faker->lastName,
            'password'              => 'secret',
            'password_confirmation' => 'secret'
        ];
    }
}
