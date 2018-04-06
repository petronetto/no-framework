<?php

declare(strict_types=1);

namespace HelloFresh\Transformers;

use League\Fractal\TransformerAbstract;
use Petronetto\ORM\ORMInterface;
use Illuminate\Database\Eloquent\Collection;

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
 *     @SWG\Property(property="average_rating", type="flot", example=4.5),
 *     @SWG\Property(property="created_at", type="string", example="2018-04-04T04:22:52+00:00"),
 *     @SWG\Property(property="updated_at", type="string", example="2018-04-04T04:22:52+00:00"),
 * )
 */
class RecipeTransformer extends TransformerAbstract
{
    /**
     * Transform any default or included data
     * into a basic array.
     *
     * @param  ORMInterface $recipe
     * @return array
     */
    public function transform(ORMInterface $recipe)
    {
        return [
            'id'              => (int) $recipe->id,
            'name'            => $recipe->name,
            'description'     => $recipe->description,
            'difficulty'      => (int) $recipe->difficulty,
            'prep_time'       => $recipe->prep_time,
            'vegetarian'      => (bool) $recipe->vegetarian,
            'average_rating'  => (float) $this->average($recipe->ratings),
            'created_at'      => $recipe->created_at->format(\DateTime::ATOM),
            'updated_at'      => $recipe->updated_at->format(\DateTime::ATOM),
        ];
    }

    /**
     * @param  Collection $ratings
     * @return string
     */
    public function average(Collection $ratings): string
    {
        $avg = $ratings->avg('rating');

        if ($avg) {
            return number_format($avg, 2, '.', '');
        }

        return "0";
    }
}
