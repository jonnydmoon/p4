<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Book;
use App\Page;
use App\CustomValidator;
use Session;
use DB;


class BookController extends Controller
{
	/**
	 * Display a listing of books, user books, and any user pages that do not have a book.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		list($books, $my_books) = Book::getPublicBooksAndUserBooks();
		$my_pages = Page::getUserPagesWithNoBooks();
		return view('books.index')->with(['books' => $books, 'my_books' => $my_books, 'my_pages' => $my_pages]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$output = $this->validateStore($request->all());

		if(count($output['errors'])){
			return response()->json($output);
		}

		$book = new Book();
		$book->name = $output['name'];
		$book->user_id = Auth::id();
		$book->save();
		return response()->json(['book'=>$book]);
	}

	 /**
	 * Validate the request for the store method.
	 *
	 * @param  $input - Associative array keyed by the name.
	 * @return $output - Associative array with clean values and errors if necessary.
	 */
	private function validateStore($input){
		$defaults = [
			'name' => ''
		];

		$input = array_merge($defaults, $input);
		$output = []; // Output are variables that will be available to the html page.
		$output['errors'] = [];
		CustomValidator::validateField($input, $output, $defaults, 'name', 'required|string|max:255', 'Invalid value for name.');        
		return $output;
	}


	/**
	 * Displays a public book, or a user's own book.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($book_id)
	{
		$book = $this->getAuthenticatedBook($book_id, true); // True is passed in to allow public books.

		if(is_null($book)) { // Validate the book is valid.
			Session::flash('flash_message','Book not found');
			return redirect('/books');
		}

		$pages = Page::where('book_id', '=', $book_id)->orderBy('name')->get(); // Get all the pages for the book.
		return view('books.show')->with(['pages' => $pages, 'book'=> $book]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$output = $this->validateStore($request->all());

		if(count($output['errors'])){
			return response()->json($output);
		}

		$book = $this->getAuthenticatedBook($id);

		if(is_null($book)) {
			return response()->json(['errors'=> ['You do not have permission to update this book.'] ]);
		}

		$book->name = $output['name'];
		$book->save();
		return response()->json(['book'=>$book]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$book = $this->getAuthenticatedBook($id);

		if(is_null($book)) {
			return response()->json(['errors'=> ['You do not have permission to delete this book.'] ]);
		}

		DB::table('pages')->where('book_id', $id)->update(['book_id' => null]); // Remove this book from all pages associated with it.

		$book->delete();
		return response()->json(['result'=> true ]);
	}

	/**
	 * Returns a user's book or public book.
	 *
	 * @param  int  $book_id
	 * @return bool $allowPublicBooks - Indicates if public books should be returned
	 */
	private function getAuthenticatedBook($book_id, $allowPublicBooks = false){
		$book = Book::find($book_id);

		if(!$book){
			return null;
		}

		if( ($allowPublicBooks && $book->is_public) || ($book->user_id === Auth::id())){
			return $book;
		}
		return null;
	}

}
