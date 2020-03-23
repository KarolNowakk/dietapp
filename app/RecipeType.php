<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeType extends Model
{
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
