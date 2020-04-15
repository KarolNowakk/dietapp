<?php

namespace App\Http\Resources;

use App\Meal as MealModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Meal extends JsonResource
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

            'recipe_id' => $this->when(isset($this->recipe->id), function () {
                if (isset($this->recipe->id)) {
                    return $this->recipe->id;
                }
            }),
            'type' => $this->when(isset($this->recipe->type), function () {
                if (isset($this->recipe->type)) {
                    return $this->recipe->type->name;
                }
            }),
            'name' => $this->when(isset($this->recipe->name), function () {
                if (isset($this->recipe->name)) {
                    return $this->recipe->name;
                }
            }),
            'spices' => $this->when(isset($this->recipe->spices), function () {
                if (isset($this->recipe->spices)) {
                    return $this->recipe->spices;
                }
            }),
            'steps' => $this->when(isset($this->recipe->steps), function () {
                if (isset($this->recipe->steps)) {
                    return $this->recipe->steps;
                }
            }),

            'meal_date' => $this->meal_date,
            'meal_number' => $this->meal_number,
            'meal_hour' => $this->meal_hour,
            'nutritions' => MealModel::find($this->id)->nutritions,
            'ingredients' => $this->ingredients,
        ];
    }
}
