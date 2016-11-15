@extends('layouts.master')

@section('title', $book->name)

@section('content')
	@foreach($pages as $page)
		<a class="page" href="{{ URL::route('page', $page->id) }}">{{ $page->name }}
			<img style="width:100%;" src="{{ URL::asset('images/pages/' . $page->outline_url) }}" />
		</a>
	@endforeach
@stop


