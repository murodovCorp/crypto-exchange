<?php

use App\ElasticSearch\UserRepository;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\MainController;
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

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::group(['prefix' => 'transactions'], function () {
        Route::post('/{id}/status-change', [TransactionController::class, 'statusChange'])->name('transactions.statusChange');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('search', [UserRepository::class, 'search'])->name('search');

    });

});

Route::get('/', [MainController::class, 'index'])->name('client.index');

Route::post('process', [MainController::class, 'process'])->name('client.process');
Route::get('process/{uuid}', [MainController::class, 'processSave'])->name('client.processSave');

require __DIR__ . '/auth.php';
