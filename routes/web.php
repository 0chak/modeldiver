<?php
use Illuminate\Support\Facades\Route;

Route::prefix('/modeldiver')
    ->controller(Chak\Modeldiver\Controllers\ModelDiverController::class)
    ->group(function(){
        Route::get('', 'index');
        Route::post('/saveposition', 'saveposition');
    });
