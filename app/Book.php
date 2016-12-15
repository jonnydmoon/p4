<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class Book extends Model
{
    public $cover;

    public function user() {
		return $this‐>belongsTo('App\User');
	}

	public function pages() {
        # Author has many Books
        # Define a one-to-many relationship.
        return $this->hasMany('App\Page');
    }

    // Returns the path for the cover image.
    public function getCoverPath(){
    	if($this->cover){
    		$url = $this->cover->colored_url ? $this->cover->colored_url :  $this->cover->outline_url;
    		return 'images/pages/thumbs/' . $url;
    	}
    }

    // Returns all the books a user can see. If a user in not logged in, only public books will be returned.
    static public function getPublicBooksAndUserBooks(){
        // Get all the public books and all the user books.
        $books = self::where('is_public', '=', 1)->get();
        $my_books = Auth::guest() ? collect([]) : self::where('user_id', '=', Auth::id())->get();

        // Get all the pages for those books so we can get the latest image to be the cover image.
        $book_ids = $books->pluck('id');
        $my_books_ids = $my_books->pluck('id');
        $ids = array_merge($book_ids->toArray(), $my_books_ids->toArray());
        $place_holders = implode(',', array_fill(0, count($ids), '?'));
        $pages =  DB::select('SELECT * FROM pages where book_id in (' .  $place_holders . ')', $ids );
        $pages = collect($pages)->keyBy('book_id')->toArray();

        // Set the cover for the 
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

        return [$books, $my_books];
    }
}
