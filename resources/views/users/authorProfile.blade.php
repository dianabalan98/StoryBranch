@extends('layouts.app')

@section('stylesheets')

	<style>
		.story-content {
			margin-top: 50px;
		}
		
		.authorDetails {
			margin-top: 20px;
		}

		.avatarRow {
			margin-top: 20px;
		}
	</style>
	
@endsection

@section('content')
	<div class="container">
    	<div class="row avatarRow">
	    	<div class="col-xs-6">

		    	<img src="/uploads/user/{{ $author['avatar'] }}" alt="{{ $author['avatar'] }}" class="avatar" >
		    </div>
		    &nbsp;&nbsp;&nbsp;&nbsp;

		    <div class="col-xs-6 authorDetails">
		    	<div id="author-username">
		    		<b>{{ $author->username }}</b>  		
		    	</div><br>
		    	<div id="author-bio">{{ $author->bio }}</div>
		    </div>
	   </div>

	   <hr>

	    <div id="stories">	
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

	    <input type="hidden" id="author_id" value="{{ $author['id'] }}">
		 
	</div>
@endsection

@section('scripts')

<!--JQUERY PENTRU GET STORIES-->
<script type="text/javascript" src="{{ URL::asset('js/authorProfileStories.js') }}"></script>

@endsection