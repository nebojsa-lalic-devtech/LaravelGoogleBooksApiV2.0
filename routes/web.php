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
Route::post('/sendmail', function (Request $request, Mailer $mailer) {
    $mailer->to($request->input('mail'))->send(new Mail($request->input('title'), $request->input('subject'), $request->input('link')));
    return redirect()->back();
})->name('sendmail');
Route::get('searchBookGoogle', 'BooksController@getBookFromGoogleApi');
Route::get('searchBookDevtech', 'BooksController@getBookFromDatabase');
Route::get('getAllBooks', 'BooksController@getAllBooks');
