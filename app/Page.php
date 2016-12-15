<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Page;
use App\Book;


class Page extends Model
{
    public function book() {
		return $this->belongsTo('App\Book');
	}

	public function user() {
		return $this->belongsTo('App\User');
	}

	// Returns all of a user's pages that are not associated with a book.
	public static function getUserPagesWithNoBooks(){
		return Auth::guest() ? collect([]) : self::where('user_id', '=', Auth::id())->whereNull('book_id')->get();
	}
}
