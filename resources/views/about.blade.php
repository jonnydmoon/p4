@extends('layouts.master')


@section('title', 'About Coloring Pages')


@section('content')
	<div class="about-page">
		<div class="about-video">
			<iframe width="560" height="315" src="https://www.youtube.com/embed/8AnRLG702Og" frameborder="0" allowfullscreen></iframe>
		</div>

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

		<h4>What people are saying about Coloring Pages:</h4>

		<blockquote>
			<p>Coloring Pages is great for my kids. It is a great way for them to be creative. I enjoy seeing all of their coloring pages. <small>Richelle – age 34</small></p>
		</blockquote>

		<blockquote>
			<p>Can I do another one? <small>Mark – age 4</small></p>
		</blockquote>

		<blockquote>
			<p>I love to color the animals!<small>Jane – age 6</small></p>
		</blockquote>
	</div>
@stop


