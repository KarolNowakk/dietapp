<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeIngridient extends Model
{
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
