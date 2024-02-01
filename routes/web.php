<?php

use App\Http\Controllers\LivetestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/live_test',[LivetestController::class, 'index']);
Route::prefix('live_test')->group(function() {
    Route::controller(LivetestController::class)->group(function(){
        Route::get('/', 'index');

    });
});
