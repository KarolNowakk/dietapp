<?php

namespace App\Http\Controllers;

use App\Substance;

class SubstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Substance::all()->pluck('name');
    }

    /**
     * Display the specified resource.
     *
     * @return Collection
     */
    public function show()
    {
        return Auth::user()->notWantedSubstances->pluck('name');
    }
}
