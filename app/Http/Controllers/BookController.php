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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::where('user_id', '=', 1)->get();
        $my_books = Book::where('user_id', '=', Auth::id())->get();

        $book_ids = $books->pluck('id');
        $my_books_ids = $my_books->pluck('id');

        $ids = array_merge($book_ids->toArray(), $my_books_ids->toArray());

        $place_holders = implode(',', array_fill(0, count($ids), '?'));


        $pages =  DB::select('SELECT * FROM pages where book_id in (' .  $place_holders . ')', $ids );

       // dd($book_ids->toArray() + $my_books_ids->toArray());


        $pages = collect($pages)->keyBy('book_id')->toArray();



        foreach($books as $book){
            if(array_key_exists($book->id, $pages)){
                $book->cover = $pages[$book->id];
            }
        }

        foreach($my_books as $book){
            if(array_key_exists($book->id, $pages)){
                $book->cover = $pages[$book->id];
            }
        }

        $my_pages = Page::where('user_id', '=', Auth::id())->whereNull('book_id')->get();

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($book_id)
    {
       
        $book = $this->getAuthenticatedBook($book_id, true);

        if(is_null($book)) {
            Session::flash('flash_message','Book not found');
            return redirect('/books');
        }

        $pages = Page::where('book_id', '=', $book_id)->orderBy('name')->get();
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

        DB::table('pages')
            ->where('book_id', $id)
            ->update(['book_id' => null]);


        $book->delete();
        return response()->json(['result'=> true ]);
    }


    private function getAuthenticatedBook($book_id, $allowPublicBooks = false){
        $book = Book::find($book_id);
        if( ($allowPublicBooks && $book->is_public) || ($book->user_id === Auth::id())){
            return $book;
        }
        return null;
    }

}
