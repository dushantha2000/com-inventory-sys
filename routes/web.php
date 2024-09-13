<?php

use App\Http\Controllers\ItemController;
use App\Models\Item;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $item = Item :: all();
    return view('home');
});

Route::post('/add_Items',[ItemController::class,'addItems']);
Route::get('/', [ItemController::class, 'showItems']);
route::delete('/items/{id}', [ItemController::class, 'deleteItem']);
Route::get('/items/{id}/edit', [ItemController::class, 'editItem']);
route::put('/items/{id}', [ItemController::class, 'update']);


