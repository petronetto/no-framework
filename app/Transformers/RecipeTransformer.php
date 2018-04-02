<?php

declare(strict_types=1);

namespace HelloFresh\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * API Response for Recipes
 *
 * @SWG\Definition(
 *     definition="RecipeApiResponse",
 *     type="object",
 *     @SWG\Property(property="id", type="integer", example=10),
 *     @SWG\Property(property="name", type="string", example="Lorem ipsum"),
 *     @SWG\Property(property="description", type="string", example="Lorem ipsum dolar net est"),
 *     @SWG\Property(property="prep_time", type="integer", example=3),
 *     @SWG\Property(property="difficulty", type="integer", example=60),
 *     @SWG\Property(property="vegetarian", type="boolean", example=true),
 *     @SWG\Property(property="average_rating", type="float", example=4.7),
 * )
 */
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
            'id'              => (int) $recipe['id'],
            'name'            => $recipe['name'],
            'description'     => $recipe['description'],
            'difficulty'      => (int) $recipe['difficulty'],
            'prep_time'       => $recipe['prep_time'],
            'vegetarian'      => (bool) $recipe['vegetarian'],
            'average_rating'  => (float) $this->average((array) $recipe['ratings']),
        ];
    }

    /**
     * @param  array $ratings
     * @return string
     */
    public function average(array $ratings): string
    {
        if (count($ratings)) {
            $avg = (array_sum($ratings) / count($ratings));

            return number_format($avg, 2, '.', '');
        }

        return "0.0";
    }
}
