<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function user() {
		return $this‐>belongsTo('App\User');
	}

	public function pages() {
        # Author has many Books
        # Define a one-to-many relationship.
        return $this->hasMany('App\Page');
    }
}
