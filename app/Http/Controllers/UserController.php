<?php

namespace App\Http\Controllers;

use App\Services\DietService;
use App\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return json
     */
    public function show()
    {
        return UserSettings::where('user_id', Auth::id())->firstOrFail();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return UserSettings
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'required_kcal' => ['numeric', 'between:1000,10000', Rule::requiredIf(function () use ($request){
                return !$request->required_proteins AND !$request->required_carbs AND !$request->required_fats;
            })],
            'required_proteins' => ['numeric', 'between:1,1000', Rule::requiredIf(function () use ($request){
                return !$request->required_kcal;
            })],
            'required_carbs' => ['numeric', 'between:1,1000', Rule::requiredIf(function () use ($request){
                return !$request->required_kcal;
            })],
            'required_fats' => ['numeric', 'between:1,1000', Rule::requiredIf(function () use ($request){
                return !$request->required_kcal;
            })],
            'meals_per_day' => ['numeric', 'between:2,8', 'required'],
            'count_by' => ['string','in:macro,kcal', 'required'],
        ]);

        $data['user_id'] = Auth::id();
        $settings = UserSettings::getDayDateOfMeal($data);

        if ($settings) {
            return response()->json($settings, 200);
        }
    }

    public function generate()
    {
        $diet = new DietService;
        return $diet->generateOneDay();
    }
}
