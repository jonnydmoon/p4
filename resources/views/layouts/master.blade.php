<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	 <title>
		{{-- Yield the title if it exists, otherwise show default --}}
		@yield('title','Coloring Pages')
	</title>
	
	<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>;
		window.app = {};
		app.user_id = <?php echo json_encode(Auth::id()); ?>; 
	</script>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700%7COswald%7CCutive+Mono%7CShadows+Into+Light+Two" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ URL::asset('css/app.css') }}" type="text/css">

	<link rel="icon" href="{{ URL::asset('favicon.ico') }}" />
	<script src="https://cdn.jsdelivr.net/lodash/4.16.3/lodash.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="{{ URL::asset('js/paint.js') }}"></script>
</head>

<body>
	<header >
			<div class="header-left">
				<div class="logo">
					<a href="{{ URL::route('root') }}"><img src="{{ URL::asset('images/logo.png') }}" /></a>
				</div>
				
				@if (!Request::is('pages*'))
					<div class="header-links">
						<a href="{{ URL::route('about') }}">About</a>
					</div>
				@endif

				@yield('toolset', '')
			</div>
			<div class="authentication-links">
			@if (Auth::guest() && !Request::is('login*'))
				<a href="{{ url('/login') }}">Login</a>
			@elseif(!Auth::guest())
				Welcome, <a href="{{ url('/my-account') }}">{{ Auth::user()->name }}</a>! 

				<a class="logout-link" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
					Logout &raquo;
				</a>

				<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			@endif

			@yield('toolset_links', '')
			</div>
	</header>

	<div id='messageArea'>
		@if(Session::get('flash_message') != null)
			<div class="alert alert-danger" data-dismiss="alert" role="alert">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Error</strong> {{ Session::get('flash_message') }}
			</div>
		@endif
		@foreach ($errors as $error)
			<div class="alert alert-danger" data-dismiss="alert" role="alert">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Error</strong> {{ $error }}
			</div>
		@endforeach
	</div>

	<div class="main">
		<main class="main-content ">
			{{-- Global error section --}}
			@if($__env->yieldContent('title'))
			<h1>
				@if(Request::is('books/*'))
					<a href="{{ url('/') }}" class="home-icon" droppable="true" data-book-id="null"><i class="fa fa-home" title="Home" aria-hidden="true"></i></a> 
				@endif
				@yield('title')
				@if($__env->yieldContent('settings'))
					@yield('settings')
				@endif
			</h1>
			@endif

			{{-- Main page content --}}
			@yield('content')
		</main>
		<div class="fullscreen-popover"></div>
	</div>
	<div class='loading-overlay'></div>



	
	<!-- TEMPLATES FOR POPOVER FORMS -->
	<script type="text/template" data-name="pageForm">
		<h1><%- data.pageTitle %></h1>
		<label>
			<span>Name:</span> <input name="name" type="text" value="<%- data.name %>" />
		</label>
		<br /><br />
		<input type="file" name="photo" id="file-input">
		<div class="small-text">* JPG or PNG format. Black and white preferred.</div>

		<button class="btn" onclick="app.hideFullscreenPopover()">Cancel</button>
		<button type="submit" name="userSubmitted" class="btn btn-success" onclick="app.addPage()">Submit</button>
	</script>

	<script type="text/template" data-name="bookForm">
		<h1>
			<%- data.pageTitle %>
			<% if(!data.isNew){ %>
				<i class="delete-entity fa fa-trash-o" title="Delete Book" aria-hidden="true" onclick="app.deleteBook(<%- data.id %>)"></i>
			<%}%>
		</h1>
		<label>
			<span>Name:</span> <input name="name" type="text" value="<%- data.name %>" />
		</label>
		<br /><br />
		<button class="btn" onclick="app.hideFullscreenPopover()">Cancel</button>
		<button type="submit" name="userSubmitted" class="btn btn-success" onclick="app.editBook(<%- data.id %>)">Submit</button>
	</script>

	<script type="text/template" data-name="saveColoringForm">
		<h1>
			<%- data.pageTitle %>
			<% if(data.isOwner){ %>
				<i class="delete-entity fa fa-trash-o" title="Delete Page" aria-hidden="true" onclick="app.deletePage(<%- data.id %>)"></i>
			<%}%>		
		</h1>
		<label>
			<span>Name:</span> <input name="name" type="text" value="<%- data.name %>" />
		</label>

		<h5>Advanced</h5>
		<div class="advanced-section">
			<input type="checkbox" id="update-black-lines" /> Update base area (your image should only be black and white).
		</div>
		<br /><br />
		<button class="btn" onclick="app.hideFullscreenPopover()">Cancel</button>
		<% if(data.isOwner){ %>
			<button type="submit" name="userSubmitted" class="btn btn-success" onclick="app.saveColoredPage()">Save</button>
		<%}%>
		<button type="submit" name="userSubmitted" class="btn btn-success" onclick="app.saveColoredPage(true)">Save As</button>
	</script>

	<script type="text/template" data-name="flashMessage">
		<div class="alert alert-danger" data-dismiss="alert" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Error</strong> <%- data.message %>
		</div>
	</script>

	
	<script src="{{ URL::asset('js/app.js') }}"></script>
	<script>
		app.baseUrl = "{{ url('/') }}"; 
	</script>
</body>
</html>