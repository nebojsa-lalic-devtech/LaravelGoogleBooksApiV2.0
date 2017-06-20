@extends('master')

@section('title', ' - Books List')

@section('content')

    <div class="col-md-6 col-md-offset-3">
        <h4>Click button to see results:</h4>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <form method="get" id="getAllForm" class="search-form" action="{{ action('BooksController@getAllBooks') }}">
            <div class="form-group has-feedback">
                <button type="submit" class="btn btn-success btn-lg">Show all Books in DevTech Library</button>
            </div>
        </form>
        @if(isset($books))
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
                @foreach ($books as $book)
                    <tr id="table_book_info" class="text-left">
                        <th id="google_isbn" scope="row">{{ $book['isbn'] }}</th>
                        <td><img src={{ $book['image_url'] }} id="google_thumbnail" WIDTH="40%"></td>
                        <td id="google_title">{{ $book['title'] }}</td>
                        <td id="google_author">{{ $book['author'] }}</td>
                        <td id="devtech_book_status" class="text-success"><b>Available</b></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        {{ $books->links() }}
        @if(isset($errorMessage))
            <h3>
                <div id="info_library">{{ $errorMessage }}</div>
            </h3>
        @endif
    </div>
@stop