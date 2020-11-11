@extends('layouts.app')

@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/comment.css') }}">
@endsection

@section('content')
	<div class="container">
		<input type="hidden" name="storyId" id="storyId" value="{{ $story->id }}">

		@include('partials.storyManagerMenu')

		<br>

		@if(!empty($errorMsg))
			<!--Daca nu avem nici un nod root-->
			<div class="alert alert-danger" role="alert">
                {{ $errorMsg }}
            </div>

		@elseif(!empty($nodes))
			<!--Cazul cand avem mai multe noduri root-->
			<div class="col-md-12">
				<p><b>Choose a beginning:</b></p>
				@foreach($nodes as $node)

					<a href="{{ route('read.readDelegate', [$story->id, $node->id]) }}" style="color: #7401DF">{{ $node->subtitle }}</a>
					<br>
				@endforeach
			</div>

		@elseif(!empty($node))
			<!--Cazul cand avem un singur nod root-->
			<input type="hidden" name="nodeId" id="nodeId" value="{{ $node->id }}">

			<div class="col-md-12">
				@if($node->display_subtitle == 1) 
					<p><b>Subtitle: {{ $node->subtitle }}</b></p><br>
				@endif
			</div>

			<div class="col-md-12">
				{!! $node->body !!}
			</div><br><br>

			@if(!empty($backTo))
				@if($backTo == "roots")
					<div class="col-md-2 choiceBack">
						<a id="backBtn" href="{{ route('read.readDelegate', [$story->id]) }}" class="btn btn-primary"><b>BACK</b></a>
					</div>
				@else
					<div class="col-md-2 choiceBack">
						<a id="backBtn" href="{{ route('read.prevNode', [$story->id, $backTo]) }}" class="btn btn-primary"><b>BACK</b></a>
					</div>
				@endif
			@endif

			<div class="col-md-8 offset-4 choiceBack">
				@if(count($relations)>0)
					<p><b>Choices: </b></p>
					@foreach($relations as $relation)
						<a class="choice" href="{{ route('read.readDelegate', [$story->id, $relation->child_id]) }}" style="color: #7401DF">{{ $relation->choice }}</a><br>
					@endforeach
				@endif
			</div>

			<div id="commentsDiv" class="col-md-12">
				<button id="displayCommentsBtn" class="btn authorBtn">Comments</button>
				<div id="comments"></div>
			</div>
		@endif

	</div>

@endsection

@section('scripts')
<script>
	// afisare si ascundere comentarii pentru fiecare fragment la btn toggle 
	jQuery(document).ready(function($){
		var nodeId = $("#nodeId").val();	

		$("#displayCommentsBtn").click(function(){	

			if($("#comments").find(".comment").length > 0) {
				$("#comments").empty();
			}
			else{
				
				$.ajax({
					url:"/getComments/" + nodeId,
					type: "get", 
					success:function(data) {
			            console.log(data);
			            if(data){
			            	

				            $.each(data , function(index, comment) {
				            	//console.log("Test: "+comment.body)
				            	comment_content = "<div class='comment' id='comment"+comment.id+"'>" +
					            				  "<div class='user-img'><img src='/uploads/user/"+comment.avatar+"' alt='"+comment.avatar+
					            				  "' class='avatar' style='width:70px;height:70px;'></div>" +
					            				  "<div class='comment-body'><a href='http://localhost:8000/userProfile/"+comment.user_id+
					            				  "''><b>"+comment.username+"</b></a>" +
					            				  "<p class='comment-date'>"+comment.created_at+"</p><br>" +
					            				  "<p>"+comment.body+"</p></div>"
				            					  "</div><br>"
				            	
				            	$('#comments').append($(comment_content));
				            	

				            });
				        }
			        }
			    });
			}
		});
	});
</script>
@endsection