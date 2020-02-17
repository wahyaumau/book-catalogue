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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('books', 'BookController');
Route::prefix('books')->group(function(){
    // Route::get('/', 'BookController@index')->name('books.index');
    // Route::post('/', 'BookController@store')->name('books.store');
    // Route::get('/create', 'BookController@create')->name('books.create');
    Route::post('/review/{id}', 'BookController@review')->name('books.review');
    Route::post('/search', 'BookController@search')->name('books.search');
    // Route::get('/{id}', 'BookController@show')->name('books.show');
    // Route::patch('/{id}', 'BookController@update')->name('books.update');
    // Route::delete('/{id}', 'BookController@destroy')->name('books.destroy');    
    // Route::get('/{id}/edit', 'BookController@edit')->name('books.edit');    
    
});


