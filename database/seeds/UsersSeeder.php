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
        // Migrating the first user
        $firstUser = [
            'username'    => 'hellofresh',
            'password'    => password_hash('supersecret', PASSWORD_BCRYPT),
            'email'       => 'hello@hellofresh.com',
            'first_name'  => 'Hello',
            'last_name'   => 'Fresh',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $this->insert('users', $firstUser);

        // Creating aditional fake users
        $faker = Faker\Factory::create();
        $data  = [];
        foreach (range(1, 10) as $index) {
            $data[] = [
                'username'    => $faker->userName,
                'password'    => password_hash('secret', PASSWORD_BCRYPT),
                'email'       => $faker->email,
                'first_name'  => $faker->firstName,
                'last_name'   => $faker->lastName,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ];
        }

        $this->insert('users', $data);
    }
}
