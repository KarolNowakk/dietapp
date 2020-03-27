<?php

namespace App\Http\Controllers;

use App\Recipe;
use Illuminate\Http\Request;
use App\Http\Resources\Recipe as RecipeResource;
use App\Product;
use App\RecipeIngredient;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RecipeResource
     */
    public function index()
    {
        $recipes = Recipe::paginate(8);
        return RecipeResource::collection($recipes);
    }

    /**
     * Display the specified resource.
     *
     * @param Recipe $recipe
     * @return RecipeResource
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param null $recipe
     * @return RecipeResource
     */
    public function store(Request $request, $recipe = NULL)
    {
        $data = $request->validate([
            'name' => ['string', 'max:255', 'required'],
            'spices' => ['string', 'required'],
            'steps' => ['required','nullable'],
            'type_id' => 'numeric',
            'is_private' => 'boolean',
            'ingredients.*.amount' => ['numeric', 'between:0,1000', 'required'],
            'ingredients.*.product_id' => ['numeric', 'required'],
        ]);

        if($data['is_private']){
            $data['user_id'] = Auth::id();
        }else{
            $data['user_id'] = 1;
        }

        $newRecipe = Recipe::updateOrCreate(['id' => $recipe], $data);

        if($newRecipe){
            $this->attachProducts($newRecipe, $data);
        }
        return new RecipeResource($newRecipe);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Recipe $recipe
     * @return RecipeResource
     * @throws \Exception
     */
    public function destroy(Recipe $recipe)
    {
        if($recipe->delete()){
            return new RecipeResource($recipe);
        }
    }

    /**
     * Attach recipe to products.
     *
     * @param Recipe $recipe
     * @param Array $data
     * @return void
     *
     */
    protected function attachProducts(Recipe $recipe, $data)
    {
        $recipe->ingredients()->detach();
        collect($data['ingredients'])->each(function($ingredient) use ($recipe){
            $product = Product::findOrFail($ingredient['product_id']);
            $ingredient['amount'] = $ingredient['amount'] / 100;
            $recipe->ingredients()->attach($product, ['amount' => $ingredient['amount']]);
        });
    }
}
