<?php

namespace App\Http\Resources;

use App\RecipeType;
use Illuminate\Http\Resources\Json\JsonResource;

class Recipe extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function toArray($request)
    // {
    //     return parent::toArray($request);
    // }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => RecipeType::findOrFail($this->type_id)->name,
            'name' => $this->name,
            'spices' => $this->spices,
            'steps' => $this->steps,
            'nutritions' => [
                $this->resource->nutritions($this->resource)
            ],
            'ingridients' =>[
                'products' => $this->resource->productsInRecipe($this->resource),
                'amounts' => $this->resource->productsAmounts($this->resource)
            ]
        ];
    }
}
