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
            'type' => $this->type->name,
            'name' => $this->name,
            'spices' => $this->spices,
            'steps' => $this->steps,
            'nutritions' => $this->nutritions,
            'ingredients' => $this->ingredients,
        ];
    }
}
