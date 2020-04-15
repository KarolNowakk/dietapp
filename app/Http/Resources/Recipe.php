<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Recipe extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->when(isset($this->type->name), function () {
                if (isset($this->type->name)) {
                    return $this->type->name;
                }
            }),
            'name' => $this->name,
            'spices' => $this->when(isset($this->spices), function () {
                if (isset($this->spices)) {
                    return $this->spices;
                }
            }),
            'steps' => $this->when(isset($this->steps), function () {
                if (isset($this->steps)) {
                    return $this->steps;
                }
            }),
            'nutritions' => $this->when(true, function () {
                return collect($this->nutritions)->map(function ($nutrient) {
                    return round($nutrient * 100, 0);
                });
            }),
            'ingredients' => $this->when(true, function () {
                return collect($this->ingredients)->map(function ($ingredient) {
                    $ingredient['amount'] *= 100;

                    return $ingredient;
                });
            }),
            'contains' => $this->substances,
        ];
    }
}
