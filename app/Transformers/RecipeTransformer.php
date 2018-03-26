<?php

declare(strict_types=1);

namespace HelloFresh\Transformers;

use League\Fractal\TransformerAbstract;

class RecipeTransformer extends TransformerAbstract
{
    /**
     * Transform any default or included data
     * into a basic array.
     *
     * @param  array $recipe
     * @return array
     */
    public function transform(array $recipe)
    {
        return [
            'id'          => (int) $recipe['id'],
            'name'        => $recipe['name'],
            'difficulty'  => (int) $recipe['difficulty'],
            'prep_time'   => $recipe['prep_time'],
            'vegetarian'  => (bool) $recipe['vegetarian'],
        ];
    }
}
