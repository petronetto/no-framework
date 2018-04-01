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
 *     @SWG\Property(property="description", type="string", example="Lorem ipsum dolar net est"),
 *     @SWG\Property(property="prep_time", type="integer", example=3),
 *     @SWG\Property(property="difficulty", type="integer", example=60),
 *     @SWG\Property(property="vegetarian", type="boolean", example=true),
 *     @SWG\Property(property="ratings", type="array", @SWG\Items(type="integer", example=5)),
 * )
 */
class Recipe extends Model
{
    /** {@inheritdoc} */
    protected $table = 'recipes';

    /** {@inheritdoc} */
    protected $casts = [
        'ratings' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'prep_time',
        'difficulty',
        'vegetarian',
        'ratings',
    ];
}
