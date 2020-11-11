@extends('layouts.app') 

@section('stylesheets')
	<!--pt buton toggle PUBLISH/UNPUBLISH-->
	<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<style>
	  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20rem; }
	  .toggle.ios .toggle-handle { border-radius: 20rem; }

	  h1 {
	  	word-wrap: break-word;
	  }
	</style>
@endsection

@section('content')
	<div class="container">

		@include('partials.storyManagerMenu')

		<br>

		
		<p id="toggleError" class="alert alert-danger" role="alert" style="display: none"></p>
			 
		
		<table class="table col-md-2 offset-8">
            <tr>
            	<td>
            		@if($story->published == 1)
	            		<input id="toggleBtn" type="checkbox" data-toggle="toggle" data-style="ios" data-on="Published" data-off="Unpublished" data-onstyle="success" checked="true">
	            	@else
	            		<input id="toggleBtn" type="checkbox" data-toggle="toggle" data-style="ios" data-on="Published" data-off="Unpublished" data-onstyle="success">
	            	@endif
	            </td>
                <td>
					<a href="{{ route('stories.edit', $story->id) }}" class="btn authorBtn">Edit</a>
				</td>
				<td>
					<form action="{{ route('stories.destroy', $story->id) }}" method="post">
						@method('delete')
						@csrf
						<input type="submit" value="Delete" class="btn authorBtn">
					</form>
				</td>
			</tr>
		</table>

		<div class="row">
		    <div class="col-sm-4 text-center overviewCoverDiv">
		    	
		    	<img src="/uploads/story/{{ $story['cover'] }}" alt="{{ $story['cover'] }}" class="cover overviewCover" >
		    </div>

		    <div class="col-sm-8 overviewContent">
		    	
		    	<h3 class="overviewTitle">{{ $story['title'] }}</h3><br>
		    	<b>Category:</b> &nbsp; <a href="#">{{ $category->name }}</a>
		    	<p><b>Tags:</b> &nbsp;
		    		@foreach($story->tags as $tag)

		    			<a href="#" class="badge badge-secondary">{{ $tag->name }}</a>

		    		@endforeach
		    	</p>
		  
		    	<hr>
		    	<h5>{{ $story['description'] }}</h5><br>  

		    	<input type="hidden" id="storyId" name="storyId" value="{{$story['id']}}">
		    </div>
   		</div><br>

	</div>

@endsection

@section('scripts')
	<!--Script pentru buton toggle de publish/unpublish-->
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
	<script>
		jQuery(document).ready(function($){
			//verific daca mai exista un nod root, altfel dezactivez butonul published
			var storyId = jQuery('#storyId').val();
        	$.ajax({
				type:"GET",
				url:"/story/checkRoot/" + storyId,
				success: function(data) {
					if(data == "false") {
	            		$("#toggleBtn").prop('checked', false).change();
	            	}
				}
			});


			// functie pt setare published/unpublished (PUT)
			$("#toggleBtn").change(function () {
				$.ajaxSetup({
		            headers: {
		                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
		            }
		        });

				var storyId = jQuery('#storyId').val();

		        if($(this).prop('checked')) {

		        	var form_data = {
			            status: "published",
			        };
		        	event.preventDefault();
		        	$.ajax({
		        		url:"/story/setStatus/" + storyId,
		        		type: "put",
		        		data: form_data,
		        		success:function(data){
		        			if (data == "ok") {
		        				$("#toggleBtn").prop( "checked", true );
		        			}
		        			else if (data == "error") {
		        				$("#toggleError").text("The story must have at least one root fragment!");
		        				$("#toggleError").show();
		        				$("#toggleBtn").prop('checked', false).change();
		        			}
		        			
		        			console.log("Error: No root node!");
		        		}
		        	})

		        }
		        else {
		        	var form_data = {
			            status: "unpublished",
			        };
		        	event.preventDefault();
		        	$.ajax({
		        		url:"/story/setStatus/" + storyId,
		        		type: "put",
		        		data: form_data,
		        		success:function(data){
		        			$("#toggleBtn").prop( "checked", false );
		        			//console.log(data);
		        		}
		        	})
		        }
	    	});
	    });
	</script>
@endsection