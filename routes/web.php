<?php


//Route::get("/book/{id}", "GeneralController@publicBook")->name("public-book");

Auth::routes();

Route::get("/my-account", "GeneralController@myAccount")->name("my-account");
Route::post("/my-account", "GeneralController@saveMyAccount");


Route::post("/coloring-page", "PageController@saveColoringPage")->name("save");
Route::post("/move-coloring-page", "PageController@moveColoringPage")->name("move-coloring-page");


Route::get("/", "BookController@index")->name("root");
Route::get("/about", function () { return view('about'); })->name("about");
Route::get('/home', 'BookController@index');



Route::resource('books', 'BookController');
Route::resource('pages', 'PageController');


/*
Listing of a user&rsquo;s saved pages	GET	user-coloring-pages.index	index	{userid}/coloring-pages
Listing of coloring pages	GET	coloring-pages.index	index	/coloring-pages
Show form to add coloring page	GET	coloring-pages.create	create	/coloring-pages/create
Process form to add coloring page	POST	coloring-pages.store	store	/coloring-pages
Show an individual coloring page	GET	coloring-pages.show	show	/coloring-pages/{id}
Show form to edit a coloring page	GET	coloring-pages.edit	edit	/coloring-pages/{id}/edit
Process form to edit coloring page	PUT	coloring-pages.update	update	/coloring-pages/{id}
Delete a given coloring page id	DELETE	coloring-pages.destroy	destroy	/coloring-pages/{id}
				
Listing of coloring books	GET	coloring-books.index	index	/coloring-books
Show form to add coloring book	GET	coloring-books.create	create	/coloring-books/create
Process form to add coloring book	POST	coloring-books.store	store	/coloring-books
Show an individual coloring book	GET	coloring-books.show	show	/coloring-books/{id}
Show form to edit a coloring book	GET	coloring-books.edit	edit	/coloring-books/{id}/edit
Process form to edit coloring book	PUT	coloring-books.update	update	/coloring-books/{id}
Delete a given color book id	DELETE	coloring-books.destroy	destroy	/coloring-books/{id}


*/