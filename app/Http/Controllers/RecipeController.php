<?php

namespace App\Http\Controllers;

use App\Http\Resources\Recipe as RecipeResource;
use App\Product;
use App\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RecipeResource
     */
    public function index()
    {
        $phrase = $request->validate([
            'phrase' => 'string',
        ]);
        $phrase = $phrase['phrase'];
        $products = Recipe::where('name', 'like', "%{$phrase}%")->paginate(10); //where('name', 'like', "%{$q}%")->paginate(10);

        if (!$products) {
            return response()->json('No recipe found', 404);
        }

        return RecipeResource::collection($products);
    }

    /**
     * Display the specified resource.
     *
     * @return RecipeResource
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param null $recipe
     *
     * @return RecipeResource
     */
    public function store(Request $request, $recipe = null)
    {
        $data = $request->validate([
            'name' => ['string', 'max:255'],
            'spices' => ['string'],
            'steps' => ['nullable'],
            'type_id' => 'numeric',
            'is_private' => 'boolean',
            'ingredients.*.amount' => ['numeric', 'between:0,1000', 'required'],
            'ingredients.*.product_id' => ['numeric', 'required'],
        ]);

        if ($data['is_private']) {
            $data['user_id'] = Auth::id();
        } else {
            $data['user_id'] = 1;
        }

        if (!array_key_exists('name', $data)) {
            $data['name'] = 'Default Recipe Name';
        }

        $newRecipe = Recipe::updateOrCreate(['id' => $recipe], $data);

        if ($newRecipe) {
            $this->attachProducts($newRecipe, $data);
        }

        return new RecipeResource($newRecipe);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Exception
     *
     * @return RecipeResource
     */
    public function destroy(Recipe $recipe)
    {
        if ($recipe->delete()) {
            return new RecipeResource($recipe);
        }
    }

    public function search(Request $request)
    {
        $phrase = $request->validate([
            'phrase' => 'string',
        ]);
        $phrase = $phrase['phrase'];
        $products = Recipe::where('name', 'like', "%{$phrase}%")->paginate(10); //where('name', 'like', "%{$q}%")->paginate(10);

        if (!$products) {
            return response()->json('No recipe found', 404);
        }

        return RecipeResource::collection($products);
    }

    /**
     * Attach recipe to products.
     *
     * @param array $data
     *
     * @return void
     */
    protected function attachProducts(Recipe $recipe, $data)
    {
        $recipe->products()->detach();
        collect($data['ingredients'])->each(function ($ingredient) use ($recipe) {
            $product = Product::findOrFail($ingredient['product_id']);
            $ingredient['amount'] = $ingredient['amount'] / 100;
            $recipe->products()->attach($product, ['amount' => $ingredient['amount']]);
        });
    }
}
