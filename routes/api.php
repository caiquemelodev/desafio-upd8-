<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\RepresentanteController;

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('cidades', CidadeController::class)->only(['index']);
Route::apiResource('representantes', RepresentanteController::class);
