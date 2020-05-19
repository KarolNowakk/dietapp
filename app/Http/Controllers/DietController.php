<?php

namespace App\Http\Controllers;

use App\Http\Resources\Meal as MealResource;
use App\Services\DietHelperService;
use App\Services\DietService;
use Illuminate\Http\Request;

class DietController extends Controller
{
    /**
     * Create diet.
     *
     * @return MealResource
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'generateDays' => ['numeric', 'between:1,14', 'required'],
        ]);
        $diet = new DietService();
        $newDiet = $diet->generateDays($data['generateDays']);

        if (!$newDiet) {
            return response()->json('Sorry, something went wrong');
        }

        return MealResource::collection($newDiet);
    }
}
