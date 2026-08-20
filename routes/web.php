<?php

use App\Http\Controllers\DashboardMenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\LookupValueController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::redirect('/', 'home');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resources([
        'users' => UserController::class,
        'roles' => RoleController::class,
        'lookup-values' => LookupValueController::class,
        'dashboard-menus' => DashboardMenuController::class,
        'job-positions' => JobPositionController::class,
    ]);
});
