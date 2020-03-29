<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Recipe;

class Meal extends Model
{
    protected $fillable = ['user_id','recipe_id', 'meal_date', 'meal_number', 'meal_hour', 'factor'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getNutritionsAttribute()
    {
        $factor = $this->factor;
        return $this->recipe->nutritions->map(function($item) use ($factor){
            return round($item * $factor);
        });
    }

    public function getIngredientsAttribute()
    {
        $factor = $this->factor;
        return collect($this->recipe->ingredients_data)->map(function($item) use ($factor){
            return [
                'name' => $item['name'],
                'amount' => round($item['amount'] * $factor),
            ];
        });
    }

}
