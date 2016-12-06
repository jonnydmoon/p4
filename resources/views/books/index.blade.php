@extends('layouts.master')

@section('title', 'Coloring Books')

@section('content')

	
	@each('partials.book', $books, 'book', 'partials.emtpy-books')


	@if (!Auth::guest())
		<h1 class="my-books">My Coloring Books</h1>
        @each('partials.book', $my_books, 'book')

        <span class="book add-book" onclick="app.showEditBook()">
			<i class="fa fa-plus-circle" aria-hidden="true"></i>
			<span >New Coloring Book</span>
		</span>

       
		<h1 class="my-books">My Extra Coloring Pages</h1>
        @each('partials.page', $my_pages, 'page')


    @endif


	

@endsection
