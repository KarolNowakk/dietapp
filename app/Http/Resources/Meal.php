<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Meal extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => $this->recipe->type->name,
            'name' => $this->recipe->name,
            'meal_date' => $this->meal_date,
            'meal_number' => $this->meal_number,
            'meal_hour' => $this->meal_hour,
            'spices' => $this->recipe->spices,
            'steps' => $this->recipe->steps,
            'nutritions' => $this->nutritions,
            'ingredients' => $this->ingredients,
        ];
    }
}
