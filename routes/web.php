<?php

use Illuminate\Support\Facades\Artisan;
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


Route::get('/', function () {
    $clearcache = Artisan::call('cache:clear');
    $clearview = Artisan::call('view:clear');
    $clearconfig = Artisan::call('config:cache');
    return 'Welcome, Broo!!!<br/>' . app()->version();
});
