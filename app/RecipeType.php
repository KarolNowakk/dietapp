<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeType extends Model
{
    protected $fillable = ['name'];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
