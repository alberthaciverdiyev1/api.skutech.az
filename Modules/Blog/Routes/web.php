<?php

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

use Modules\Blog\Http\Controllers\BlogController;

Route::prefix('soluna')->name('admin:')->middleware(['auth', 'role_or_permission:developer|admin|show dashboard'])->group(function() {
    Route::resource('/blogs', BlogController::class);
});
