<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	 <title>
		{{-- Yield the title if it exists, otherwise show default --}}
		@yield('title','Developer\'s Best Friend')
	</title>
	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700%7COswald%7CCutive+Mono" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="css/app.css" type="text/css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
	<link rel="icon" href="favicon.ico" />
	<script   src="http://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha256-/SIrNqv8h6QGKDuNoLGA4iret+kyesCkHGzVUUV0shc=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/ace/1.2.4/min/ace.js"></script>
</head>

<body class="fullscreen">

	{{-- Global error section --}}
	<div class='alerts'>
	@foreach ($errors as $error)
		<div class="alert alert-danger"  data-dismiss="alert" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Error</strong> {{ $error }}
		</div>
	@endforeach
	</div>


	{{-- Main page content --}}
	@yield('content')

	<script src="js/app.js"></script>
</body>
</html>