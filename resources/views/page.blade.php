@extends('layouts.master')

@section('toolset')
	<div id="toolset">
		<div id='paints'></div>

		<div id="tools">
			<svg width="145" height="27" viewBox="0 0 145 27" data-tool="marker">
			    <path fill="#097d4b" d="M911.658,9.824h124.6l0.89,2.044,16.91,7.231a8.748,8.748,0,0,1,.94,3.355,7.921,7.921,0,0,1-.83,3.344l-17.02,7.428-0.89,2.156h-124.6A51.96,51.96,0,0,1,910,23.221,65.352,65.352,0,0,1,911.658,9.824Z" transform="translate(-910 -9)"/>
			    <path fill="#249866" d="M1032.72,36H916.191a48.951,48.951,0,0,1-2.045-13,58.271,58.271,0,0,1,2.045-14H1032.72a56.7,56.7,0,0,1,2,13.723A52.468,52.468,0,0,1,1032.72,36ZM899.663,20.492" transform="translate(-910 -9)"/>
			</svg>

			<svg id="Wand" width="145" height="28" viewBox="0 0 145 28" data-tool="bucket">
			  <path fill="#a1a1a1" d="M910,49.927l128.89,5.049v2.049L910,62.073V49.927Z" transform="translate(-910 -42)"/>
			  <path fill="#ff8d28" d="M1041.24,42l1.72,7.469,5.16-5.593-2.18,7.343L1053.16,49l-5.5,5.25L1055,56l-7.34,1.75,5.5,5.25-7.22-2.219,2.18,7.343-5.16-5.593L1041.24,70l-1.72-7.469-5.16,5.593,2.18-7.343L1029.32,63l5.5-5.25L1027.48,56l7.34-1.75-5.5-5.25,7.22,2.219-2.18-7.343,5.16,5.593Z" transform="translate(-910 -42)"/>
			</svg>

			<svg width="142.72" height="19.969" viewBox="0 0 152.72 20.969" data-tool="eraser">
			  <path  style="fill: #f66262;" d="M901.989,93.658c2.837-3.933,8.737-10.942,11.76-14.926A6.9,6.9,0,0,1,919.5,76H1052c1.92,0,3.39.948,1.82,3.155-2.93,4.1-7.61,11.51-10.52,15.2-0.94,1.188-.56,2.648-5.63,2.619C1017.41,96.861,923.4,96.61,904.336,96.2,903.138,96.175,901,95.023,901.989,93.658Z" transform="translate(-901.75 -76)"/>
			</svg>
		</div>

		<svg viewBox="0 0 100 100" class="brush-size">
			<circle data-brush="100" cx="50" cy="50" r="50"  />
			<circle data-brush="60"  cx="50" cy="50" r="30"  />
			<circle data-brush="40"  cx="50" cy="50" r="20"  />
			<circle data-brush="20"  cx="50" cy="50" r="10"  />
			<circle data-brush="10"  cx="50" cy="50" r="5"   />
			<circle data-brush="5"   cx="50" cy="50" r="2.5" />
		</svg>

		<i class="fa fa-floppy-o save-page-button" aria-hidden="true"></i>

	</div>
@stop



@section('content')
	
	<div class="canvas-background">
		<div id="canvas-div"></div>
	</div>

	 
    <script type="text/javascript">
    	drawingApp.init('canvas-div', 900, 800, "{{ URL::asset('images/') }}", "{{ URL::asset('images/pages/' . $page->outline_url) }}", "{{ $page->colored_url ? URL::asset('images/colored-pages/' . $page->colored_url) : '' }}");
	</script>



	<form method="POST" action="{{ URL::asset('page') }}" enctype="multipart/form-data" id="color-form">
		{{ csrf_field() }}
		<input type="hidden" name="img" id="hidden-img-field">
		<input type="hidden" name="name" id="hidden-name-field">

	</form>


@stop


