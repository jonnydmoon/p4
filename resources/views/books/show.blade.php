@extends('layouts.master')

@section('title', $book->name)

@if(!Auth::guest())
	@section('settings')
		<i class="edit-entity fa fa-cog" title="Edit Book" aria-hidden="true" onclick="app.showEditBook(true)"></i>
	@stop
@endif

@section('content')
	@each('partials.page', $pages, 'page')

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


