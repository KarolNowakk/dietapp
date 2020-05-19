<?php

namespace App\Http\Controllers;

use App\Services\ShoppingListService;

class ShoppingListController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $from
     * @param string $to
     *
     * @return Collection
     */
    public function show($from, $to)
    {
        return ShoppingListService::create($from, $to);
    }
}
