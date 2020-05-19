<?php

namespace App\Services;

use App\Product;

class RecipeService
{
    public static function createName($ingredients)
    {
        return collect($ingredients)->sortByDesc('amount')->map(function ($product) {
            return Product::findOrFail($product['product_id'])->name;
        })->join(', ');
    }
}
