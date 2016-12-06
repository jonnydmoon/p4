<a class="book" href="{{ URL::route('books.show', $book->id) }}" ondragover="app.onDragOverBook(event)"  ondragenter="app.onDragEnterBook(event)"  ondragleave="app.onDragLeaveBook(event)" ondrop="app.onDropBook(event, {{$book->id}})">
	{{ $book->name }}
	@if($book->getCoverPath())
		<img style="width:100%;" src="{{ URL::asset($book->getCoverPath()) }}" />
	@endif
</a>
