@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
	    <div class="col-xs-6">
	    	<p>Stories Index Page</p>
	    </div>
   </div>

   <hr>

	    @foreach($stories as $story)
	    	<div class="row">
	    		<div class="col-md-3">
	    			<img src="/uploads/story/{{ $story->cover }}" alt="{{ $story->cover }}" class="cover" >
	    		</div>

	    		<div class="col-md-9">
	    			<h3>{{ $story->title }}</h3><br>
	    			<h4>{{ $story->description }}</h4><br>
	    			<p>Tags: &nbsp;
			    		@foreach($story->tags as $tag)

			    			<a href="#" class="badge badge-secondary">{{ $tag->name }}</a>

			    		@endforeach
		    		</p><br>
	    			<a href="{{ route('stories.show', $story->id) }}" class="btn btn-info">Read</a> 
	    		</div>
	    	</div>
	    	<br>

	    @endforeach

</div>
@endsection