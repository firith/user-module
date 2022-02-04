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

use Modules\User\Http\Controllers\UserController;

Route::name('usermodule.admin.')->prefix('admin/users')->middleware(['auth'])->group(function () {
    Route::get('/', \Modules\User\Http\Livewire\Users\UserTable::class)->name('users.index');
    Route::get('create', \Modules\User\Http\Livewire\Users\CreateForm::class)->name('users.create');
    Route::get('{user}/edit', \Modules\User\Http\Livewire\Users\EditForm::class)->name('users.edit');
});
