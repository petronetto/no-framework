<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class Rating
 *
 * @SWG\Definition(
 *     definition="Rating",
 *     type="object",
 *     @SWG\Property(property="id", type="integer", example=1),
 *     @SWG\Property(property="rating", type="integer", example=5),
 *     @SWG\Property(property="created_at", type="string", example="2018-04-01 12:00:00"),
 *     @SWG\Property(property="updated_at", type="string", example="2018-04-01 12:00:00"),
 * )
 */
class Rating extends Model
{
    /** {@inheritdoc} */
    protected $table = 'ratings';

    /** {@inheritdoc} */
    protected $fillable = [
        'recipe_id',
        'rating',
    ];
}
