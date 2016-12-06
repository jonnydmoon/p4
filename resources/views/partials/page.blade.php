<a class="page" href="{{ URL::route('pages.show', $page->id) }}" draggable="true" ondragstart="app.onPageDragStart(event, {{$page->id}})">{{ $page->name }}
	<img style="width:100%;" src="{{ URL::asset('images/pages/thumbs/' . $page->colored_url) }}" />
	<img style="width:100%;" src="{{ URL::asset('images/pages/thumbs/' . $page->outline_url) }}" />
</a>
