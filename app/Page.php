<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
