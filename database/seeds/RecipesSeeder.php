<?php declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class RecipesSeeder extends AbstractSeed
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
        $data  = [];
        foreach (range(1, 30) as $index) {
            $data[] = [
                'name'        => $faker->word,
                'prep_time'   => $faker->numberBetween($min = 10, $max = 120),
                'difficulty'  => $faker->numberBetween($min = 1, $max = 3),
                'vegetarian'  => $faker->numberBetween($min = 0, $max = 1),
            ];
        }

        $this->insert('recipes', $data);
    }
}
