<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\Http\Controllers\ExchangeRateController@index');

Route::post('/save', 'App\Http\Controllers\ExchangeRateController@save');

Route::get('/save', 'App\Http\Controllers\ExchangeRateController@getSaved');

Route::post('/search', 'App\Http\Controllers\ExchangeRateController@search');

Route::get('/delete/{id}', 'App\Http\Controllers\ExchangeRateController@deleteSaved');
