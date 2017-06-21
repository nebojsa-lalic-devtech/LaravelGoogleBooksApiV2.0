<?php

use App\Http\Controllers\BooksController;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use App\Mail\Mail;

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
    return view('layouts.welcome');
});
Route::get('/search', function () {
    return view('layouts.search');
});
Route::get('/list', function () {
    return view('layouts.list');
});
Route::get('/about', function () {
    return view('layouts/about');
});
Route::post('/sendmail', 'BooksController@sendMail')->name('sendmail');
Route::get('searchBookGoogle', 'BooksController@getBookFromGoogleApi');
Route::get('searchBookDevtech', 'BooksController@getBookFromDatabase');
Route::get('getAllBooks', 'BooksController@getAllBooks');
Route::get('addBook', 'BooksController@addBook');
