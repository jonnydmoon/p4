<a class="page" href="{{ URL::route('pages.show', $page->id) }}" draggable="true" ondragstart="app.onPageDragStart(event, {{$page->id}})">{{ $page->name }}
	@if($page->colored_url) 
		<img src="{{ URL::asset('images/pages/thumbs/' . $page->colored_url) }}" />
	@endif
	<img src="{{ URL::asset('images/pages/thumbs/' . $page->outline_url) }}" />
</a>
