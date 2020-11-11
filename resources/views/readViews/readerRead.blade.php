@extends('layouts.app') 

@section('stylesheets')

	<!--JQUERY MODAL CSS trebuie pus inainte de CSS propriu-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/comment.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/ratings/emotions.css') }}">
	<style>
		
		.modal {
			width: 350px;
			height: 400px;
		}

		#scoreModal input a{
			display: block; 
			margin: 0 auto;
		}

		#scoreSubmitBtn {
			color: white;
			border-radius: 20px;
		}

		.error {
			color: #8B71F0;
			margin-bottom: 10px;
			display: block; 
			margin: 0 auto;
		}


		.title {
			word-wrap: break-word;
		}

	</style>


@endsection

@section('content')
	<div class="container">
		<input type="hidden" name="storyId" id="storyId" value="{{ $story->id }}">
		<div id="story-title" class="col-md-12">
			<h3 class="title">{{ $story->title }}</h3>
			by: <b><a href="http://localhost:8000/userProfile/{{ $story->author_id }}" id="author_id"></a></b>
		</div>
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

					<a class="rootNodes" id="choice{{$node->id}}" href="{{ route('read.readDelegate', [$story->id, $node->id]) }}" style="color: #7401DF">{{ $node->subtitle }}</a>
					<br>
				@endforeach
			</div>

		@elseif(!empty($node))
			<!--Cazul cand avem un singur nod root-->

			<div class="col-md-12">
				@if($node->display_subtitle == 1) 
					<p><b>Subtitle: {{ $node->subtitle }}</b></p><br>
				@endif
				<input type="hidden" name="nodeId" id="nodeId" value="{{ $node->id }}">
			</div>

			<div class="col-md-12">
				{!! $node->body !!}
			</div><br><br>

			@if(!empty($backTo))
				@if($backTo == "roots")
					<div class="col-md-2 choiceBack">
						<a id="backBtn" href="{{ route('read.readDelegate', [$story->id]) }}" class="btn"><b>BACK</b></a>
					</div>
				@else
					<div class="col-md-2 choiceBack">
						<a id="backBtn" href="{{ route('read.prevNode', [$story->id, $backTo]) }}" class="btn"><b>BACK</b></a>
					</div>
				@endif
			@endif

			<div class="col-md-6 offset-1 choiceBack">
				@if(count($relations)>0)
					<p><b>Choices: </b></p>
					@foreach($relations as $relation)
						<a class="choice" id="choice{{$relation->child_id}}" href="{{ route('read.readDelegate', [$story->id, $relation->child_id]) }}" style="color: #7401DF">{{ $relation->choice }}</a><br>
					@endforeach
				@endif
			</div>

			@include('partials.rating')
			@include('partials.comment')


		@endif

		<!--STICKY SIDE MENU PENTRU APRECIERI POVESTE-AUTOR-->
		@include('partials.favoriteStickyMenu')


		<!--SCORE MODAL-->
		<div id="scoreModal" class="modal text-center">
		 	<p>Rate this storyline:</p>
		 	<input id="score" type="number" min="1" max="10" required/><br><br>
		 	<a id="scoreSubmitBtn" class="btn btn-primary" rel="modal:close">Submit<a/>
		</div>

	</div>

@endsection


@section('scripts')
	
	<!--JQUERY PENTRU COMENTARII-->
	<script type="text/javascript" src="{{ URL::asset('js/commentCRUD.js') }}"></script>

	<!--JQUERY PENTRU FAVORITES-->
    <script type="text/javascript" src="{{ URL::asset('js/favoriteCRUD.js') }}"></script>

    <!--JQUERY PENTRU EMOTIONS RATINGS-->
    <script type="text/javascript" src="{{ URL::asset('js/ratings/emotions.js') }}"></script>

    <script>
    	jQuery(document).ready(function($){

			var storyId = $("#storyId").val();	
			// cerere GET pt verificare daca cititorul = autorul
			$.ajax({
				url:"/getAuthor/" + storyId,
				type: "get", 
				success:function(data) {
		            console.log(data['username']);
		            $('#author_id').text(data['username']);
		        }
		    });

			// face ratingul de sentimente obligatoriu
		    $(".choice").click(function(e) {
		    	if (!$(".emotion").is(':checked')) {
		    		alert("Please add a rating!");
		    		e.stopPropagation();
    				e.preventDefault();
		    	}
		    });


		    // functii pentru rating system => le voi pune ulterior in fisier .js separat
		    // adaugare/actualizare path nod + emotie in variabila de sesiune
		    var nodeId = $("#nodeId").val();

		    $("input[name='emotion']").change(function() {

		    	var emotion = $("input[name='emotion']:checked").val();

		    	$.ajaxSetup({
		            headers: {
		                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
		            }
		        });

		        $.ajax({
	        		url:"/addRating/" + nodeId + "/" + emotion,
	        		type: "post",
	        		success:function(data){
	       	        			
	        			console.log(data);
	        			checkIfEndOfPath();
	    
	        		}
	        	});
		    });

		    // preluarea ultimului sentiment din sesiune cand dau back   
	    	$.ajax({
				url:"/getRating/" + nodeId,
				type: "get", 
				success:function(data) {
		           
		            if(data) {
		            	$("input[name='emotion'][value='" + data + "']").prop("checked",true);
		            }
		            
		        }
		    });
		     
		    

		    function checkIfEndOfPath() {
				if($('a.rootNodes').length == 0 && $('a.choice').length == 0) {

					// verific daca cititorul a citit deja acest path si a dat scor
					checkPath();
					
				}
			}

			function checkPath() {
				$.ajax({
					url:"/checkStorylineRating/" + storyId,
					type: "get", 
					success:function(data) {
						//console.log(data);
			           	if(data) {
							// afisez modal cu "You already rated this storyline"
							
							getRecommendation(data);
							// obtin recomandare

						}
						if(!data) {	

							// show score modal
							$('#scoreModal').modal('show');

							// modalul nu poate fi inchis decat dupa setarea scorului
							$("#scoreModal").modal({
							  escapeClose: false,
							  clickClose: false,
							  showClose: false
							});

						}		            
			        }
			    });
			}


			function getRecommendation(storyline) {
				//console.log(storyline);
				$.ajax({
					url:"/getRecommendation",
					type: "post", 
					data: {
						storyline: storyline
					},
					success:function(data) {
						console.log("Rec: "+data);

						if ($("#recModal").length) {
							
							showRecModal();
						}
						else if(!($("#recModal").length)) {
							if(data) {
								createRecModal(data);
							}
						}
						
					}
				});
			}

			function createRecModal(data) {
				var storylines = JSON.parse(data);
				rec_modal = "<div id='recModal' class='modal text-center'><p>Recommended storylines</p>"


				$.each(storylines, function (index, storyline) {
					
					rec_modal += "<p>"+index+") <b><a href='http://localhost:8000/story/overview/"+storyline.story_id+"' class='recommendations' id='storyline"+storyline.id+"'>"+storyline.story_title+"</a></b></p><br>"
					
				});

				rec_modal += "<a class='btn btn-primary close-modal' rel='modal:close' href='#'>Close<a/></div>"

				$('.container').append($(rec_modal));
				showRecModal();
			}

			// cand se da click pe unul din linkurile de recomandare
			// cand se da click pe unul din firele narative recomandate il pregatesc pt salvare in sesiune
			$(function() {
			    $(document).on('click', '.recommendations', function(e) {
			    
			    	var storyline_id = $(this).prop('id').replace(/[^0-9]/gi, '');
			    	//alert("S-A DAT CLICK pe id: "+storyline_id);

			    	$.ajaxSetup({
			            headers: {
			                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			            }
			        });

					$.ajax({
						url:"/setRecSession/" + storyline_id,
						type: "post", 
						success:function(data) {
				       		//alert(data);
				        }
				    });
					
			    });
			});
			
			
			// verificare daca am variabila de sesiune rec_nodes
			$.ajax({
				url:"/getRecSession/" + storyId,
				type: "get", 
				success:function(data) {

		            if(data) {
		           		// tratez cazurile de highlight choices pt recomandare
		           		var decoded_data = jQuery.parseJSON(data);
		           		var nodes = decoded_data['nodes'];
		           		//alert(nodes);

		           		// caz multiple roots
		           		// SCHIMBARE CULOARE ALEGERE RECOMANDATA
		           		if($(".rootNodes").length) {
		           			
		           			$('#choice'+nodes[0]).css('background-color','#64F1B8');
		           			
		           		}

		           		// caz only one node
		           		else if($("#nodeId").length) {
		           			// SCHIMBARE CULOARE ALEGERE RECOMANDATA
		           			
		           			var idxNodCurent = nodes.indexOf(nodeId);
		           			$('#choice'+nodes[idxNodCurent+1]).css('background-color','#64F1B8');
		           			
		           		}
		            }
		        }
		    });




			function showRecModal() {
				if ($("#recModal").length) {

					$('#recModal').modal('show');
					
				}
			}


			//cand se da click pe butonul submit din modal => ma asigur ca se completeaza scorul
			$("#scoreSubmitBtn").hover(function(e) {
				if(!($("#score").val())) {

					if ($("#scoreErrror").length == 0) {
						error = "<label id='scoreErrror' class='error'>Add a score!</label>"
						$('#scoreModal').append($(error));
					}
						
					$("#scoreSubmitBtn").prop("disabled",true);
				}
				else if($("#score").val() >= 1 && $("#score").val() <= 10) {

					if($("#scoreErrror").length) {
						$("#scoreErrror").remove();
					}
					$("#scoreSubmitBtn").prop("disabled",false);
				}
				else if($("#score").val() < 1 || $("#score").val() > 10) {
					$("#scoreSubmitBtn").prop("disabled",true);
				}			
			});


			// salvare ratings + score
			$("#scoreSubmitBtn").click(function() {
				if($("#score").val() >= 1 && $("#score").val() <= 10) {
					
					var score = $("#score").val();
					console.log(score);

					$.ajaxSetup({
			            headers: {
			                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			            }
			        });

			        $.ajax({
		        		url:"/saveStorylineRating/" + storyId + "/" + score,
		        		type: "post",
		        		success:function(data){
		       	        			
		        			console.log("Rec: " + data);
		        			if(data) {
		        				createRecModal(data);
		        			}
		        			
		    
		        		}
		        	});
				}
			});

		});
    </script>

    <!--JQUERY MODAL-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <!--VALIDARI COMMENT-->
    <script type="text/javascript" src="{{ URL::asset('js/validations/commentValidations.js') }}"></script>

@endsection