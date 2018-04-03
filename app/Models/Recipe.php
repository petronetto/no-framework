<?php

declare(strict_types=1);

namespace HelloFresh\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Recipe
 *
 * @SWG\Definition(
 *     definition="Recipe",
 *     type="object",
 *     @SWG\Property(property="id", type="integer", example=10),
 *     @SWG\Property(property="name", type="string", example="Lorem ipsum"),
 *     @SWG\Property(property="description", type="string", example="Lorem ipsum dolar net est"),
 *     @SWG\Property(property="prep_time", type="integer", example=3),
 *     @SWG\Property(property="difficulty", type="integer", example=60),
 *     @SWG\Property(property="vegetarian", type="boolean", example=true),
 *     @SWG\Property(property="ratings", type="array", @SWG\Items(type="integer", example="[5,4,5,3]")),
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

    /**
     * Undocumented function
     *
     * @param  [type] $query
     * @param  [type] $search
     * @return void
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->whereRaw('searchtext @@ plainto_tsquery(\'english\', ?)', [$search])
            ->orderByRaw('ts_rank(searchtext, plainto_tsquery(\'english\', ?)) DESC', [$search]);
    }
}
