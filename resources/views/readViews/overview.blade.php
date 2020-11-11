@extends('layouts.app')


@section('content')
	
	<div class="container overviewContainer">
		<input type="hidden" name="storyId" id="storyId" value="{{ $story->id }}">
		
		<div class="col-md-12 row">
			<div class="col-sm-4 text-center overviewCoverDiv">
				<img src="/uploads/story/{{ $story['cover'] }}" alt="{{ $story['cover'] }}" class="cover overviewCover" >
			</div>
			<div class="col-md-8 overviewContent">

				<h3 class="overviewTitle">{{ $story['title'] }}</h3> 
				<b>By: </b><a href="http://localhost:8000/userProfile/{{$story['author_id']}}">{{ $author['username'] }}</a><br>
				<b>Category: </b>&nbsp; <a href="#">{{ $category['name'] }}</a>
				<p>Tags: &nbsp;
		    		@foreach($story['tags'] as $tag)

		    			<a href="#" class="badge badge-secondary">{{ $tag->name }}</a>

		    		@endforeach
	    		</p>
		        <hr>

				<h5>{{ $story['description'] }}</h5><br>  
				
				<a href="{{ route('read.startReading', $story['id']) }}" class="btn readBtn startReadingBtn">Start reading</a>
					
					
				</div>
			</div>			
		</div>
		

		<!--STICKY SIDE MENU PENTRU APRECIERI POVESTE-AUTOR-->
		@include('partials.favoriteStickyMenu')
		
	</div>
	
@endsection

@section('scripts')
	<!--JQUERY PENTRU FAVORITES-->
    <script type="text/javascript" src="{{ URL::asset('js/favoriteCRUD.js') }}"></script>
@endsection