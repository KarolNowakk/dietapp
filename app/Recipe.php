<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function type()
    {
        return $this->belongsTo(RecipeType::class);
    }

    public function getNutritionsAttribute()
    {
        $kcal = 0;
        $proteins = 0;
        $carbs = 0;
        $fats = 0;

        $this->loadMissing('ingredients.product');

        foreach ($this->ingredients as $ingredient) {
            $kcal += $ingredient->accumulated_kcal;
            $proteins += $ingredient->accumulated_proteins;
            $carbs += $ingredient->accumulated_carbs;
            $fats += $ingredient->accumulated_fats;
        }

        return [
            'kcal' => round($kcal, 0),
            'proteins' => round($proteins, 0),
            'carbs' => round($carbs, 0),
            'fats' => round($fats, 0),
        ];
    }


    public function getIngredientsDataAttribute()
    {
        $data = collect();
        $this->loadMissing('ingredients.product');
        $this->ingredients->each(function (RecipeIngredient $ingredient) use ($data) {
            /*
             * TODO: Handle case when product doesn't exist
             * */
            $data->push([
                'amount' => $ingredient->amount,
                'name' => $ingredient->product->name,
            ]);
        });

        return $data->toArray();
    }
}
