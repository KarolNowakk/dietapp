<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kcal' => $this->kcal,
            'proteins' => $this->proteins,
            'carbs' => $this->carbs,
            'fats' => $this->fats,
            'saturated_fats' => $this->saturated_fats,
            'polysaturated_fats' => $this->polysaturated_fats,
            'monosaturated_fats' => $this->monosaturated_fats,
        ];
    }
}
