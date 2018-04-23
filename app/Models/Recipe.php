<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\hasMany;

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
 *     @SWG\Property(property="created_at", type="string", example="2018-04-01 12:00:00"),
 *     @SWG\Property(property="updated_at", type="string", example="2018-04-01 12:00:00"),
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
        'name',
        'description',
        'prep_time',
        'difficulty',
        'vegetarian',
    ];

    /**
     * Ratings relationship
     *
     * @return BelongsTo
     */
    public function ratings(): hasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * @param  Builder $query
     * @param  string  $search
     * @return Builder
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
