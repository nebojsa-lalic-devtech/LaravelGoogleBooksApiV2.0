<?php

namespace App\Http\Controllers;

use App\Book;
use App\Mail\Mail;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BooksController extends Controller
{
    public $client;

    # GET ONE BOOK BY ID
    /**
     * @return string
     */
    public function getBookFromDatabase()
    {
        $isbn = $_GET['isbn']; // valid isbn numbers: 9781491924440, 9781785283291, 9789332517868
        try {
            $book = Book::where('isbn', $isbn)->where('available', 1)->firstOrFail();
            if ($book) {
                $bookStatus = 'available';
                return $bookStatus;
            }
        } catch (ModelNotFoundException $ex) {
            $bookStatus = 'not available';
            return $bookStatus;
        }
    }

    #GET BOOK FROM GOOGLE
    /**
     * @param Request $request
     * @return $this
     */
    public function getBookFromGoogleApi(Request $request)
    {
        $this->validate($request, [
            'isbn' => 'numeric|digits:13'
        ]);
        try {
            $isbn = $_GET['isbn'];
            $this->client = new Client();
            $body = $this->client->get('https://www.googleapis.com/books/v1/volumes?q=' . $isbn)->getBody();
            $response = json_decode($body);
            if ($response->totalItems != 0) {
                $response->bookDevTechStatus = $this->getBookFromDatabase();
                return view('layouts.search')->with(compact('response'));
            } else {
                throw new NotFoundHttpException();
            }
        } catch (\Exception $ex) {
            $errorMessage = '***  Sorry, book with ISBN "' . $isbn = $_GET['isbn'] . '" not exist in GoogleBook Library  ***';
            return view('layouts.search')->with(compact('errorMessage'));
        }
    }

    #GET ALL BOOKS FROM LIBRARY
    /**
     * @return $this
     */
    public function getAllBooks()
    {
        try {
            $books = Book::paginate(3);
            return view('layouts.list')->with(compact('books'));
        } catch (\Exception $ex) {
            $errorMessage = '***  Sorry, Library is empty  ***';
            return view('layouts.list')->with(compact('errorMessage'));
        }
    }

    // NEED REFACTORING -->>> ****************************************
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addBook(Request $request)
    {
        $isbn = $_GET['current_isbn'];
        $this->client = new Client();
        $body = $this->client->get('https://www.googleapis.com/books/v1/volumes?q=' . $isbn)->getBody();
        $response = json_decode($body);
        $book = new Book();
        $book->isbn = $isbn;
        $book->image_url = $response->items[0]->volumeInfo->imageLinks->thumbnail;
        $book->title = $response->items[0]->volumeInfo->title;
        $book->author = $response->items[0]->volumeInfo->authors[0];
        $book->available = 1;
        $book->save();

        return redirect()->back();
    }
    // <<<-- **********************************************************

    #SEND NEW MAIL
    /**
     * @param Request $request
     * @param Mailer $mailer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMail(Request $request, Mailer $mailer)
    {
        $mailer->to($request->input('mail'))->send(new Mail($request->input('mailMessage'), $request->input('subject'), $request->input('link')));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBook(Request $request)
    {
        $isbn = $_GET['delete_by_this_isbn'];
        $book = Book::where('isbn', $isbn);
        $book->delete();
        return redirect()->back();
    }
}