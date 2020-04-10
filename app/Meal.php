<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['user_id', 'recipe_id', 'meal_date', 'meal_number', 'meal_hour', 'factor'];

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
                'amount' => $item['pivot']['amount'],
            ];
        });
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getAllProducts()
    {
        $notDeletedProducts = $this->getThings();
        $addedProducts = $this->getProductsAddedToRecipe();

        return $notDeletedProducts->merge($addedProducts);
    }

    public function getThings()
    {
        $productsNotIncluded = collect($this->products)->map(function ($item) {
            if (true == $item->pivot->not_include) {
                return $item->id;
            }
        })->toArray();

        return collect($this->recipe->products)->transform(function ($item) use ($productsNotIncluded) {
            if (!in_array($item['id'], $productsNotIncluded)) {
                $item['pivot']['amount'] *= $this->factor;

                return $item;
            }
        })->filter()->flatten(1);
    }

    // public function getProductsNotDeletedFromRecipe()
    // {
    //     $this->loadMissing('recipe.products');
    //     $productsNotIncluded = collect($this->products)->map(function ($item) {
    //         if (true == $item->pivot->not_include) {
    //             return $item->id;
    //         }
    //     })->toArray();

    //     return collect($this->recipe->products)->transform(function ($item) use ($productsNotIncluded) {
    //         if (!in_array($item['id'], $productsNotIncluded)) {
    //             return $item;
    //         }
    //     })->filter()->flatten(1);

    //     // $notDeletedProducts = collect($this->recipe->ingredients)->filter(function ($item) use ($productsNotIncluded) {
    //     //     return !in_array($item['id'], $productsNotIncluded);
    //     // })->flatten(1);
    //     // $amount = $notDeletedProducts[1]['pivot']['amount'];
    //     // foreach ($notDeletedProducts as $item) {
    //     //     $item['pivot']['amount'] = $amount * $this->factor;
    //     // }

    //     // return $notDeletedProducts;
    //     // // return $notDeletedProducts->transform(function ($item) {
    //     // //     return $item['pivot']['amount'] * $this->factor;
    //     // // });
    // }

    protected function products()
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
