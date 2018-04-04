<?php declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class RecipesSeeder extends AbstractSeed
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data  = [];
        foreach (range(1, 30) as $index) {
            $data[] = [
                'name'        => $faker->word,
                'description' => $faker->paragraph($nbSentences = 15, $variableNbSentences = true),
                'prep_time'   => $faker->numberBetween($min = 10, $max = 120),
                'difficulty'  => $faker->numberBetween($min = 1, $max = 3),
                'vegetarian'  => $faker->numberBetween($min = 0, $max = 1),
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ];
        }

        $this->insert('recipes', $data);
    }
}
