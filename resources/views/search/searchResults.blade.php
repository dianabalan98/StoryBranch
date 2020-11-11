@extends('layouts.app')

@section('stylesheets')
	<style>
		.title {
			word-wrap: break-word;
		}

		.storyFilter {
			margin-top: 50px;
			margin-bottom: 50px;
		}

		.storyDiv {
			margin-bottom: 30px;
		}

		.container {
			background-color: white;
			width: 100%;
			display: flex;
		}

		#stories-content {
			margin-bottom: 20px;
		}

		#categoryFilter {
			width: 170px;
			border-radius: 25px;
		}

		
	</style>
@endsection

@section('content')
	<div class="container">
		@if(!empty($errorResult))
			<div class="alert alert-danger" role="alert">
			  {{$errorResult}}
			</div>
		
		@else(!empty($results))

			<div id="stories" class="row col-md-12">	
				<!--FILTRU DUPA CATEGORIE DOAR DIN JQUERY FARA CERERE HTTP-->
				<div class="storyFilter">
					<select class="selectStyle search" id="categoryFilter" name="categoryFilter[]">
				     	<option selected disabled>Filter by category...</option>
				    </select>
				</div> 
				
				<div id="stories-content" class="row col-md-12">			
				</div>		
			</div>

			<div id="ModalStory" class="modal fade" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		      		<!--AICI VINE CONTINUTUL DETALIAT AL PREFETEI FIECAREI POVESTI PE CARE SE DA HOVER-->
		      		<div class="modal-header storyModalHeader">
		      			<!--TITLU POVESTE-->
		      			<!--NUME AUTOR COMPLET-->
		      		</div>
		      		<div class="modal-body">
		      			<!--CATEGORIE-->
		      			<!--TAGS-->
		      			<!--DESCRIERE-->
		      		</div>
		      		<div class="modal-footer storyModalFooter">
		      			<!--BUTON CITIRE-->
		      		</div>
		      </div>
		    </div>
		  </div>
		
		@endif

	</div>
@endsection


@section('scripts')
<script type="text/javascript" src="{{ URL::asset('js/search/searchResultsFilter.js') }}"></script>

<script type="text/javascript">
jQuery(document).ready(function($){

	var stories = {!! json_encode($results) !!};  //preiau variabila php in var jquery
	console.log(stories);

	$.each(stories , function(index, story) { 
		
		title = '';
	    len_title = story['title'].length;
	    if(len_title > 20) {
	   		title = story['title'].substring(0, 20);
	   		title += '...';
	   	}
	   	else title = story['title'];

	    content = "<div class='col-md-3 offset-md-1 storyHolderMain'>" +
	   			  "<div class='storyHolderCover' id='cover"+story['id']+"' data-target='ModalStory' data-toggle='modal'><img src='/uploads/story/"+story['cover']+"' alt='"+story['cover']+"' class='cover'></div>" +
	   			  "<div class='storyHolderDetails'><p class='title'>"+title+"</p><b> By: <a href='http://localhost:8000/userProfile/"+story['author_id']+"'>"+story['author']+"</a></b><br>" +
	   			  "<b>Category: </b>&nbsp; <a href='#' class='storyCategory'>"+story['category']+"</a></div>"

	   	
	   	id = story['id'];
	   	content += "<a href='http://localhost:8000/story/overview/"+id+"' class='btn readBtn'>Read</a>"
	   	content += "</div>"

	    $('#stories-content').append($(content));
	});


	// functie de afisare modal cu detalii poveste
	$(document).on("mouseenter", ".storyHolderCover", function() {

		var stringId =  $(this).attr('id');
		var id = stringId.replace(/[^0-9]/g,'');
		var currStory = stories.filter( function(story){return (story['id']==id);} );
		console.log(currStory[0]['title']);
		
	    $('.modal-header').empty();
	    $('.modal-body').empty();
	    $('.modal-footer').empty();

	    modalHeader = "<div>"+currStory[0]['title']+"</div>";

			modalBody = "<div><b>By: <a href='http://localhost:8000/userProfile/"+currStory[0]['author_id']+"'>"+currStory[0]['author']+"</a></b></div>";    	
	   	modalBody += "<div><b>Category: </b>&nbsp; <a href='#'>"+currStory[0]['category']+"</a></div>";	            		

	    if(currStory[0]['tags'].length > 0) {
	   		tags = "<div><p class='tags'>Tags: </p>"
		   	$.each(currStory[0]['tags'], function(index_tag, tag) {

		   		tagContent = "<a href='#' class='badge badge-secondary'>"+tag+"</a>"
		   		tags += tagContent;
		   	});
		   	tags += "</div>"
		   	modalBody += tags;
	   	}
	   	modalBody += "<div class='justifyText'>"+currStory[0]['description']+"</div>";

	   	modalFooter = "<div><a href='http://localhost:8000/story/overview/"+currStory[0]['id']+"' class='btn readBtn'>Read</a></div>"

	   	$('.modal-header').append(modalHeader);
	   	$('.modal-body').append(modalBody);
	   	$('.modal-footer').append(modalFooter);

	   	jQuery('#ModalStory').modal('show');  
	    //$('#ModalStory').modal('show');  nu functioneaza decat cu jQuery explicit!!        
	});

});
</script>

@endsection