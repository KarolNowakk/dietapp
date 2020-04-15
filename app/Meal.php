<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['user_id', 'recipe_id', 'meal_date', 'meal_number', 'meal_hour', 'factor'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getNutritionsAttribute()
    {
        $kcal = 0;
        $proteins = 0;
        $carbs = 0;
        $fats = 0;

        foreach ($this->getAllProducts() as $product) {
            $kcal += $product->kcal * $product->pivot->amount / 100;
            $proteins += $product->proteins * $product->pivot->amount / 100;
            $carbs += $product->carbs * $product->pivot->amount / 100;
            $fats += $product->fats * $product->pivot->amount / 100;
        }

        return [
            'kcal' => round($kcal, 0),
            'proteins' => round($proteins, 0),
            'carbs' => round($carbs, 0),
            'fats' => round($fats, 0),
        ];
    }

    public function getIngredientsAttribute()
    {
        $allProducts = $this->getAllProducts();

        return collect($allProducts)->map(function ($item) {
            return [
                'product_id' => $item['pivot']['product_id'],
                'name' => $item['name'],
                'amount' => round($item['pivot']['amount'], 0),
            ];
        });
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getAllProducts()
    {
        $notDeletedProducts = $this->getProductsNotDeletedFromRecipe();
        $addedProducts = $this->getProductsAddedToRecipe();

        return $notDeletedProducts->merge($addedProducts);
    }

    public function getProductsNotDeletedFromRecipe()
    {
        $productsNotIncluded = $this->products->filter(function ($item) {
            return $item->pivot->not_include;
        })->pluck('id')->toArray();

        if (!isset($this->recipe)) {
            return collect();
        }

        return $this->recipe->products->map(function ($item) use ($productsNotIncluded) {
            if (!in_array($item['id'], $productsNotIncluded)) {
                $item['pivot']['amount'] *= $this->factor;

                return $item;
            }
        })->filter()->flatten(1);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_meal')->withPivot('not_include', 'amount');
    }

    protected function getProductsAddedToRecipe()
    {
        return collect($this->products)->filter(function ($item) {
            return false == $item->pivot->not_include;
        })->flatten(1);
    }
}
