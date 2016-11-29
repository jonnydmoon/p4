@extends('layouts.master')



@section('title', 'Your Coloring Books')

@section('content')
	
	@each('partials.book', isset($books) ? $books : [], 'book', 'partials.empty-books')

	<span onclick="app.showEditBook()">Add Coloring Book</span>


@endsection
