<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public function ingridients()
    {
        return $this->hasMany(RecipeIngridient::class);
    }

    public function nutritions($recipe)
    {
        $kcal = 0;
        $proteins = 0;
        $carbs = 0;
        $fats = 0;
        foreach ($recipe->ingridients as $ingridient) {
            $kcal = round($kcal + $ingridient->product->kcal*$ingridient->amount/100,0);
            $proteins = round($proteins + $ingridient->product->proteins*$ingridient->amount/100,0);
            $carbs = round($carbs + $ingridient->product->carbs*$ingridient->amount/100,0);
            $fats = round($fats + $ingridient->product->fats*$ingridient->amount/100,0);
        }
        return [
            'kcal' => $kcal,
            'proteins' => $proteins,
            'carbs' => $carbs,
            'fats' => $fats
        ];
    }

    public function productsInRecipe($recipe)
    {
        $productsTable = [];
        foreach ($recipe->ingridients as $ingridient) {
            array_push($productsTable,$ingridient->product->name);
        }
        return $productsTable;
    }

    public function productsAmounts($recipe)
    {
        $productsTable = [];
        foreach ($recipe->ingridients as $ingridient) {
            array_push($productsTable,$ingridient->amount);
        }
        return $productsTable;
    }
}
