<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['id', 'name', 'spices', 'steps', 'type_id', 'is_private', 'user_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('amount');
    }

    public function type()
    {
        return $this->belongsTo(RecipeType::class);
    }

    public function recipe()
    {
        return $this->hasMany(Meal::class);
    }

    public function getNutritionsAttribute()
    {
        $kcal = 0;
        $proteins = 0;
        $carbs = 0;
        $fats = 0;

        foreach ($this->products as $ingredient) {
            $kcal += $ingredient->kcal * $ingredient->pivot->amount / 100;
            $proteins += $ingredient->proteins * $ingredient->pivot->amount / 100;
            $carbs += $ingredient->carbs * $ingredient->pivot->amount / 100;
            $fats += $ingredient->fats * $ingredient->pivot->amount / 100;
        }

        return collect([
            'kcal' => $kcal,
            'proteins' => $proteins,
            'carbs' => $carbs,
            'fats' => $fats,
        ]);
    }

    public function getIngredientsAttribute()
    {
        $data = collect();
        $this->products->each(function ($ingredient) use ($data) {
            // TODO: Handle case when product doesn't exist
            $data->push([
                'product_id' => $ingredient->id,
                'amount' => round($ingredient->pivot->amount, 2),
                'name' => $ingredient->name,
            ]);
        });

        return $data->toArray();
    }

    public function getSubstancesAttribute()
    {
        return $this->products->map(function ($product) {
            return $product->substances;
        })->flatten(1)->pluck('name')->unique();
    }
}
