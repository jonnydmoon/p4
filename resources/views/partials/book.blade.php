<a class="book" href="{{ URL::route('books.show', $book->id) }}" droppable="{{ Auth::user() && $book->user_id == Auth::user()->id ? 'true' : 'false' }}" data-book-id="{{$book->id}}">
	{{ $book->name }}
	@if($book->getCoverPath())
		<img style="width:100%;" src="{{ URL::asset($book->getCoverPath()) }}" />
	@endif
</a>
