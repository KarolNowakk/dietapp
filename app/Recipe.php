<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['id', 'name', 'spices', 'steps', 'type_id', 'is_private', 'user_id'];

    public function ingredients()
    {
        return $this->belongsToMany(Product::class)->withPivot('amount');
    }

    public function type()
    {
        return $this->belongsTo(RecipeType::class);
    }

    public function recipe()
    {
        return $this->hasOne(Meal::class);
    }

    public function getNutritionsAttribute()
    {
        $kcal = 0;
        $proteins = 0;
        $carbs = 0;
        $fats = 0;

        foreach ($this->ingredients as $ingredient) {
            $kcal += $ingredient->kcal * $ingredient->pivot->amount / 10;
            $proteins += $ingredient->proteins * $ingredient->pivot->amount / 10;
            $carbs += $ingredient->carbs * $ingredient->pivot->amount / 10;
            $fats += $ingredient->fats * $ingredient->pivot->amount / 10;
        }

        return collect([
            'kcal' => round($kcal, 0),
            'proteins' => round($proteins, 0),
            'carbs' => round($carbs, 0),
            'fats' => round($fats, 0),
        ]);
    }

    public function getIngredientsDataAttribute()
    {
        $data = collect();

        $this->ingredients->each(function ($ingredient) use ($data) {
            /*
             * TODO: Handle case when product doesn't exist
             * */
            $data->push([
                'amount' => round($ingredient->pivot->amount * 10,1),
                'name' => $ingredient->name,
            ]);
        });

        return $data->toArray();
    }

}
