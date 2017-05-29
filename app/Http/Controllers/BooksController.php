<?php
/**
 * Created by PhpStorm.
 * User: nebojsa.lalic
 * Date: 26-May-17
 * Time: 10:47 AM
 */

namespace App\Http\Controllers;

use App\Book;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BooksController extends Controller
{
    public $client;

    # GET ONE BOOK BY ID
    /**
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getBookFromDatabase()
    {
        $isbn = $_GET['search']; // valid isbn numbers: 9781491924440, 9781785283291, 9789332517868
        try {
            $book = Book::where('isbn', $isbn)->firstOrFail();
            if (response()->json($book)) {
                $bookStatus = 'available';
                return $bookStatus;
            }
//            return response()->json($book);
        } catch (ModelNotFoundException $ex) {
            return 'Sorry, this book is not in DevTech library! :(';
        }
    }

    /**
     * @return array|string
     * @throws NotFoundHttpException
     */
    public function getBookFromGoogleApi()
    {
        try {
            $isbn = $_GET['search'];
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
            $errorMessage = '***  Sorry, book with ' . $isbn = $_GET['search'] . ' not exist in GoogleBook Library  ***';
            return view('layouts.search')->with(compact('errorMessage'));
        }
    }
}