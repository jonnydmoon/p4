<a class="book" href="{{ URL::route('books.show', $book->id) }}" data-droppable="{{ Auth::user() && $book->user_id == Auth::user()->id ? 'true' : 'false' }}" data-book-id="{{$book->id}}">
	{{ $book->name }}
	@if($book->getCoverPath())
		<img alt="Coloring Book" src="{{ URL::asset($book->getCoverPath()) }}" />
	@endif
</a>
