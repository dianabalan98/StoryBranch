@extends('layouts.app')

@section('stylesheets')
	<style>
		#stories, #authors {
			margin-top: 20px;
		}

		
		
	</style>
@endsection

@section('content')

	<div class="container" id="favorites">
		
		<br>
		<div id="fav-buttons">
			<button class="btn favListBtn" id="fav-stories-btn">Favorite stories</button>
			<button class="btn favListBtn" id="fav-authors-btn">Favorite authors</button>
		</div>

	</div>

@endsection


@section('scripts')
	<script>
		jQuery(document).ready(function($){
	
			// preluare povesti preferate
			$("#fav-stories-btn").on('click', function(){
				$.ajax({
					url:"/getFavoriteStories",
					type: "get", 
					success:function(data) {

			            if($('#authors').length)  $('#authors').remove();
			            if($( "#stories" ).length)
					    {
					       $("#stories").empty();
					    }

			            stories_list = "<div id='stories'></div>"
			            $('#favorites').append($(stories_list));

			            $.each(data , function(index, list) {
			            	
			            	$.each(list , function(story, s) {
			            		
				            	story_link = "<div><a class='title favLinks' href='http://localhost:8000/story/overview/"+s['id']+"'>"+s['title']+"</a></div>"
				            	$('#stories').append($(story_link));
				            });
			            });
			        }
			    });
			});

			// preluare autori preferati
			$("#fav-authors-btn").on('click', function(){
				$.ajax({
					url:"/getFavoriteAuthors",
					type: "get", 
					success:function(data) {

			            if($('#stories').length)  $('#stories').remove();
			            if($( "#authors" ).length)
					    {
					       $("#authors").empty();
					    }

			            authors_list = "<div id='authors'></div>"
			            $('#favorites').append($(authors_list));

			            $.each(data , function(index, list) {
			            	
			            	$.each(list , function(author, a) {
			            		
				            	author_link = "<div><a class='title favLinks' href='http://localhost:8000/userProfile/"+a['id']+"'>"+a['username']+"</a></div>"
				            	$('#authors').append($(author_link));
				            });
			            });
			        }
			    });
			});
		});
	</script>
@endsection