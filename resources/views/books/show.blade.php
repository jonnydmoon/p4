@extends('layouts.master')

@section('title', $book->name)

@if(!Auth::guest())
	@section('settings')
		<i class="fa fa-cog" aria-hidden="true" onclick="app.showEditBook(true)"></i>
	@stop
@endif

@section('content')
	@foreach($pages as $page)
		<a class="page" href="{{ URL::route('pages.show', $page->id) }}">{{ $page->name }}
			<img style="width:100%;" src="{{ URL::asset('images/pages/' . $page->outline_url) }}" />
		</a>
	@endforeach

	@if(!Auth::guest())
	<span class="page add-page" onclick="app.showEditPage()">
		<i class="fa fa-plus-circle" aria-hidden="true"></i>
		<span >New Coloring Page</span>
	</span>
	@endif

	<script>
		app.currentBook = <?php echo json_encode($book); ?>;
	</script>
@stop


