<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function getCoverPath(){
    	if($this->cover){
    		return 'images/pages/thumbs/' . $this->cover->outline_url;
    	}
    }
}
