@extends('layouts.master')


@section('title', 'About Coloring Pages')


@section('content')
	<div class="about-page">
		<h4>Coloring Pages provide fun and relaxing entertainment for all ages.</h4>
		<p>Welcome to Coloring Pages- home of online coloring pages. Here you can easily create online coloring pages for your kids using images you find on the web or by uploading your own drawings.</p>

		<p>Coloring Pages help promote creativity in children. In addition, children can save all of their work and build a collection of their own personalized colored pages. </p>
		
		<a href="{{ url('/') }}">Start coloring &raquo;</a> @if (Auth::guest())| <a href="{{ url('/login') }}">Log in to create coloring pages &raquo;</a> @endif

		<h4>Coloring Pages offer many features and benefits:</h4>
		<ul>
			<li>Create your own, or color an existing page</li>
			<li>Organize pages into books by categories or by child.</li>
			<li>The interface is intentionally simple to keep children from clicking away from books or pages.</li>
			<li>Enjoyable for all ages</li>
			<li>Save progress of your coloring page</li>
		</ul>
	</div>
@stop


