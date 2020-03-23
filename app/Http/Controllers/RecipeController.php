<?php

namespace App\Http\Controllers;

use App\Recipe;
use Illuminate\Http\Request;
use App\Http\Resources\Recipe as RecipeResource;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::paginate(8);
        RecipeResource::withoutWrapping();
        return RecipeResource::collection($recipes);
    }

    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        RecipeResource::withoutWrapping();
        //return $recipe->ingredients;
        return new RecipeResource($recipe);
    }
}
