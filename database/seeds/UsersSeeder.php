<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data  = [];
        foreach (range(1, 10) as $index) {
            $data[] = [
                'username'    => $faker->userName,
                'password'    => password_hash('secret', PASSWORD_DEFAULT),
                'email'       => $faker->email,
                'first_name'  => $faker->firstName,
                'last_name'   => $faker->lastName,
                // 'created_at'  => $faker->dateTime($max = 'now', $timezone = null),
                // 'updated_at'  => $faker->dateTime($max = 'now', $timezone = null),
            ];
        }

        $this->insert('users', $data);
    }
}
