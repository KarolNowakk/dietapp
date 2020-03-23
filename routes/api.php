<?php

use App\Http\Controllers\AuthController;
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

Route::post('/login','AuthController@login');
Route::middleware('auth:api')->post('/logout','AuthController@logout');
Route::post('/register','AuthController@register');

Route::get('products', 'ProductController@index');
Route::get('product/{id}', 'ProductController@show');
Route::post('product', 'ProductController@store');
Route::put('product', 'ProductController@store');
Route::delete('product/{id}', 'ProductController@destroy');

Route::get('recipes', 'RecipeController@index');
Route::get('recipe/{id}', 'RecipeController@show');