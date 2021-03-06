@extends('master')

@section('title', ' - Search')

@section('content')

    <div class="col-md-6 col-md-offset-3">
        <h4>Search book by Google ISBN number:</h4>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <form method="get" id="bookSearchForm" class="search-form"
              action="{{ action('BooksController@getBookFromGoogleApi') }}">
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
                    <th>Email book to someone</th>
                    <th>Add to Library</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr id="table_book_info" class="text-left">
                    <th id="google_isbn"
                        scope="row">
                        @if(strlen($response->items[0]->volumeInfo->industryIdentifiers[0]->identifier) == 13)
                            {{ $response->items[0]->volumeInfo->industryIdentifiers[0]->identifier }}
                        @else
                            {{ $response->items[0]->volumeInfo->industryIdentifiers[1]->identifier }}
                        @endif
                    </th>
                    <td class="col-md-2"><img src={{ $response->items[0]->volumeInfo->imageLinks->thumbnail }} id="google_thumbnail"
                             WIDTH="40%"></td>
                    <td class="col-md-2" id="google_title"><a
                                href={{ $response->items[0]->volumeInfo->infoLink }}>{{ $response->items[0]->volumeInfo->title }}</a>
                    </td>
                    <td id="google_author">{{ $response->items[0]->volumeInfo->authors[0] }}</td>
                    @if($response->bookDevTechStatus == 'available')
                        <td id="devtech_book_status" class="text-success"><b>{{ $response->bookDevTechStatus }}</b></td>
                    @else
                        <td id="devtech_book_status" class="text-danger"><b>{{ $response->bookDevTechStatus }}</b></td>
                    @endif
                    <td class="col-md-2">
                        <button id="send_book" onclick="showOrHideMailInput()" style="display: inline-block">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> email book
                        </button>
                    </td>
                    <td class="col-md-2">
                        @if($response->bookDevTechStatus == 'not available')
                            <div>
                                <form method="get" action="{{ action('BooksController@addBook') }}">
                                    <button type="submit" id="add_book_to_library" onclick="showMessageForAddedBook()"
                                            style="display: inline">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> add book
                                    </button>
                                    @if(strlen($response->items[0]->volumeInfo->industryIdentifiers[0]->identifier) == 13)
                                        <input type="text" style="visibility: hidden" name="current_isbn"
                                               value="{{ $response->items[0]->volumeInfo->industryIdentifiers[0]->identifier }}"/>
                                    @else
                                        <input type="text" style="visibility: hidden" name="current_isbn"
                                               value="{{ $response->items[0]->volumeInfo->industryIdentifiers[1]->identifier }}"/>
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        @else
                            <div>
                                <button type="button" disabled class="text-success">already added</button>
                            </div>
                        @endif
                    </td>
                    <td class="col-md-2">
                        @if($response->bookDevTechStatus == 'available')
                        <form method="get" action="{{ action('BooksController@deleteBook') }}">
                            <button type="submit" id="add_book_to_library"  style="display: inline" onclick="showMessageForDeletedBook()">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> delete book
                            </button>
                            @if(strlen($response->items[0]->volumeInfo->industryIdentifiers[0]->identifier) == 13)
                                <input type="text" style="visibility: hidden" name="delete_by_this_isbn"
                                       value="{{ $response->items[0]->volumeInfo->industryIdentifiers[0]->identifier }}"/>
                            @else
                                <input type="text" style="visibility: hidden" name="delete_by_this_isbn"
                                       value="{{ $response->items[0]->volumeInfo->industryIdentifiers[1]->identifier }}"/>
                            @endif
                        @else
                        @endif
                            {{ csrf_field() }}
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
            <div id="send_book_div" class="col-md-6 col-md-offset-3" style="display:none">
                <h4>Please, populate all fields before sending email:</h4>
                <form action="{{ route('sendmail') }}" method="post">
                    <div class="form-group">
                        <label class="sr-only" for="contact-email">Email</label>
                        <input type="email" name="mail" placeholder="Email..." class="contact-email form-control"
                               id="contact-email">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="contact-email">Subject</label>
                        <input type="text" name="subject" placeholder="Subject..." class="contact-email form-control"
                               id="contact-email">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="contact-message">Message</label>
                        <textarea name="mailMessage" placeholder="Message..." class="contact-message form-control"
                                  id="contact-message"></textarea>
                    </div>
                    <div class="form-group" style="visibility: hidden">
                        <label class="sr-only" for="link"></label>
                        <input value="{{ $response->items[0]->volumeInfo->infoLink }}" name="link"
                               class="contact-message form-control"
                               id="link"/>
                    </div>
                    <input type="submit" class="btn btn-success btn-lg" value="Send Mail!"
                           onclick="showProgressBar();"/>
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="progress col-md-6 col-md-offset-3" id="progress_bar" style="display:none; margin-top: 10px;">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                     aria-valuemin="0" aria-valuemax="100" style="width: 85%">
                </div>
            </div>
            <div style="display:none" class="alert alert-success" role="alert" id="successfully_send_mail">Well done!
                You successfully send book.
            </div>
            <div style="display:none" class="alert alert-success" role="alert" id="successfully_added_book">Well done!
                You successfully add new book to Library.
            </div>
            <div style="display:none" class="alert alert-danger" role="alert" id="successfully_deleted_book">You successfully deleted a book from Library.
            </div>
        @endif
        @if(isset($errorMessage))
            <h3>
                <div id="info_library" class="text-danger">{{ $errorMessage }}</div>
            </h3>
        @endif
    </div><br>
    <script src="{{ URL::asset('js/sendMail.js') }}"></script>
    <script src="{{ URL::asset('js/addBook.js') }}"></script>
    <script src="{{ URL::asset('js/deleteBook.js') }}"></script>
@stop