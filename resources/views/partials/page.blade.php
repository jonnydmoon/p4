<a class="page" href="{{ URL::route('pages.show', $page->id) }}" draggable="{{ Auth::user() && $page->user_id == Auth::user()->id ? 'true' : 'false' }}" data-page-id="{{$page->id}}">{{ $page->name }}
	<img src="{{ URL::asset('images/pages/thumbs/' . ($page->colored_url ? $page->colored_url : $page->outline_url)) }}" />
</a>
