<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ShoppingListService
{
    public static function create($from, $to)
    {
        $meals = Auth::user()->meals->whereBetween('meal_date', [$from, $to]);

        $shoppingList = collect();

        $meals->each(function ($meal) use ($shoppingList) {
            $meal->ingredients->each(function ($ingredient) use ($shoppingList) {
                if (!$shoppingList->contains('name', $ingredient['name'])) {
                    $shoppingList->push($ingredient);
                } else {
                    $shoppingList->each(function ($item) use ($ingredient) {
                        if ($item['name'] === $ingredient['name']) {
                            $item['amount'] += $ingredient['amount'];
                        }
                    });
                }
            });
        });

        return $shoppingList;
    }
}
