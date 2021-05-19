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

Route::get('/instituicoes','App\Http\Controllers\Api\InstituicoesController@index')->name('instituicoes');

Route::get('/convenios','App\Http\Controllers\Api\ConveniosController@index')->name('convenios');

Route::post('/simulacao','App\Http\Controllers\Api\SimulacaoController@index')->name('simulacao');




