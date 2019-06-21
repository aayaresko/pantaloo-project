<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestrictionCategoriesCountry extends Model
{
    protected $table = 'restriction_categories_by_country';

    protected $fillable = ['category_id', 'code_country', 'mark'];
}
