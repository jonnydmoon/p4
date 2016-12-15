<?php
Auth::routes();

Route::get("/my-account", "GeneralController@myAccount")->middleware('auth')->name("my-account.edit");
Route::put("/my-account", "GeneralController@saveMyAccount")->middleware('auth')->name("my-account.update");

Route::get("/", "BookController@index")->name("root");
Route::get("/about", function () { return view('about'); })->name("about");
Route::get('/home', 'BookController@index');

Route::resource('books', 'BookController', ['except' => ['create', 'edit']]); // The create and edit views are handled with client side templates.
Route::resource('pages', 'PageController', ['except' => ['index', 'create', 'edit', 'update']]); // Create and edit are handled client side. The update is handled with the "save" route.
Route::post("/coloring-page", "PageController@saveColoringPage")->name("save-coloring-page");
Route::post("/move-coloring-page", "PageController@moveColoringPage")->name("move-coloring-page");
