@extends('layouts.master')

@section('title', ' - Search')

@section('content')

    <div class="col-md-6 col-md-offset-3">
        <h4>Search book by Google ISBN number:</h4>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <form method="get" id="bookSearchForm" class="search-form" action="{{ action('BooksController@getBookFromGoogleApi') }}">
            <div class="form-group has-feedback">
                <label for="search" class="sr-only">Search</label>
                <input type="text" class="form-control" name="isbn" id="isbn" placeholder="search">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </form>
        @if(count($errors) > 0)
            <div class="alert alert-danger" id="info_library">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(isset($response))
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ISBN</th>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>DevTech Library</th>
            </tr>
            </thead>
            <tbody>
            <tr id="table_book_info" class="text-left">
                <th id="google_isbn" scope="row">{{ $response->items[0]->volumeInfo->industryIdentifiers[0]->identifier }}</th>
                <td><img src={{ $response->items[0]->volumeInfo->imageLinks->thumbnail }} id="google_thumbnail" WIDTH="40%"></td>
                <td id="google_title">{{ $response->items[0]->volumeInfo->title }}</td>
                <td id="google_author">{{ $response->items[0]->volumeInfo->authors[0] }}</td>
                <td id="devtech_book_status">{{ $response->bookDevTechStatus }}</td>
            </tr>
            </tbody>
        </table>
        @endif
    </div>
@stop