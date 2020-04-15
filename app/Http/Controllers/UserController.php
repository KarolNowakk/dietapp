<?php

namespace App\Http\Controllers;

use App\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
     * @return UserSettings
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'required_kcal' => ['numeric', 'between:1000,10000', Rule::requiredIf(function () use ($request) {
                return !$request->required_proteins and !$request->required_carbs and !$request->required_fats;
            })],
            'required_proteins' => ['numeric', 'between:1,1000', Rule::requiredIf(function () use ($request) {
                return !$request->required_kcal;
            })],
            'required_carbs' => ['numeric', 'between:1,1000', Rule::requiredIf(function () use ($request) {
                return !$request->required_kcal;
            })],
            'required_fats' => ['numeric', 'between:1,1000', Rule::requiredIf(function () use ($request) {
                return !$request->required_kcal;
            })],
            'meals_per_day' => ['numeric', 'between:2,8', 'required'],
            'count_by' => ['string', 'in:macro,kcal', 'required'],
            'start' => ['date_format:H:i', 'before:end', 'required'],
            'end' => ['date_format:H:i', 'required'],
        ]);

        $data['user_id'] = Auth::id();

        $existingSettings = UserSettings::where('user_id', $data['user_id'])->first();

        if ($existingSettings) {
            $existingSettings = $existingSettings['id'];
        }
        $settings = UserSettings::updateOrCreate(['id' => $existingSettings], $data);

        if ($settings) {
            return response()->json($settings, 200);
        }
    }
}
