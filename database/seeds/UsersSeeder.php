<?php declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];
        foreach (range(1, 10) as $index) {
            $data[] = [
                'username'  => $faker->userName,
                'password'  => password_hash('secret', PASSWORD_DEFAULT),
                'email'     => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
            ];
        }

        $this->insert('users', $data);
    }
}
