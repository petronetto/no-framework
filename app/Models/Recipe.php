<?php

declare(strict_types=1);

namespace HelloFresh\Models;

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
        'name', 'prep_time', 'difficulty', 'vegetarian',
    ];
}
