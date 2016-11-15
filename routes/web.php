<?php

Route::get("/", "GeneralController@home")->name("home");
Route::get("/public-book/{id}", "GeneralController@publicBook")->name("public-book");
Route::get("/page/{id}", "GeneralController@page")->name("page");

Route::post("/page", "GeneralController@save")->name("save");