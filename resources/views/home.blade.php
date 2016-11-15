@extends('layouts.master')

@section('content')
	@foreach($books as $book)
		<a class="book" href="{{ URL::route('public-book', $book->id) }}">
			{{ $book->name }}
		</a>
	@endforeach
@stop


