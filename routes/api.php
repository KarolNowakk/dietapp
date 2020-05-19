<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'AuthController@login');
Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
Route::post('/register', 'AuthController@register');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/recipe/search', 'RecipeController@index');
    Route::match(['post', 'put'], '/recipe/{recipe?}', 'RecipeController@store');
    Route::delete('/recipe/{recipe}', 'RecipeController@destroy');
    Route::get('/recipe', 'RecipeController@show');

    Route::match(['post', 'put'], '/user', 'UserController@store');
    Route::get('/user', 'UserController@show');

    Route::post('/diet/generate', 'DietController@create');

    Route::get('/substances', 'SubstanceController@index');
    Route::get('/substances/user', 'SubstanceController@show');

    Route::get('/shopping_list/{from}_{to}', 'ShoppingListController@show');

    Route::get('/meals/{date}', 'MealController@index');
    Route::get('/meal/{date}/{number}', 'MealController@show');
    Route::put('/meal/{meal}', 'MealController@update');
    Route::post('/meal', 'MealController@store');
});

Route::post('/product/search', 'ProductController@index');
Route::match(['post', 'put'], '/product/{product?}', 'ProductController@store');
Route::delete('/product/{product}', 'ProductController@destroy');
Route::get('/product/{product}', 'ProductController@show');

$table = [1, 1, 2];
