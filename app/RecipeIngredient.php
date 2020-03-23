<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{

    protected function accumulateElement($name)
    {
        return $this->product->$name * $this->amount / 100;
    }

    public function getAccumulatedProteinsAttribute()
    {
        return $this->accumulateElement('proteins');
    }

    public function getAccumulatedCarbsAttribute()
    {
        return $this->accumulateElement('carbs');
    }

    public function getAccumulatedFatsAttribute()
    {
        return $this->accumulateElement('fats');
    }

    public function getAccumulatedKcalAttribute()
    {
        return $this->accumulateElement('kcal');
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
