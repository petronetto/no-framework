<?php

declare(strict_types=1);

namespace HelloFresh\Models;

/**
 * Class Recipe
 *
 * @SWG\Definition(
 *     definition="Recipe",
 *     type="object",
 *     @SWG\Property(property="name", type="string", example="Lorem ipsum"),
 *     @SWG\Property(property="prep_time", type="integer", example=3),
 *     @SWG\Property(property="difficulty", type="integer", example=60),
 *     @SWG\Property(property="vegetarian", type="boolean", example=true),
 *     @SWG\Property(property="rating", type="float", example=4.7),
 * )
 */
class Recipe extends Model
{
    /** {@inheritdoc} */
    protected $table = 'recipes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'prep_time', 'difficulty', 'vegetarian', 'rating',
    ];
}
