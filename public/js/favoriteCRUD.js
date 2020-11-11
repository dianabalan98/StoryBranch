jQuery(document).ready(function($){

	var storyId = $("#storyId").val();	
	// cerere GET pt verificare daca cititorul = autorul
	$.ajax({
		url:"/checkIfReaderIsAuthor/" + storyId,
		type: "get", 
		success:function(data) {
            console.log(data);
            if(data == 'true') {

            	$("#favoriteMenu").empty();
            	$('#favoriteMenu').hide();
            }
            else {

            	getFavoritesData();
            }
        }
    });

	// cerere GET pt favorites
	function getFavoritesData() {
		$.ajax({
			url:"/checkFavorites/" + storyId,
			type: "get", 
			success:function(data) {
	            //console.log(data);
	            if(data['fav_story'] == true) {

	            	$('#fav-story-icon').addClass('icon-heart').removeClass('icon-heart-empty');
	            }
	            if(data['fav_author'] == true) {

	            	 $('#fav-author-icon').addClass('icon-star').removeClass('icon-star-empty');
	            }
	        }
	    });
	}
	

	// cereri pt favorite story
	$("#fav-story").click(function(){

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	        }
	    });

		if($(".icon-heart-empty").length){
		    $.ajax({
				url:"/addFavoriteStory",
				type: "post", 
				data: {
					story_id: storyId
				},
				success:function(data) {
		            console.log(data);
		            $('#fav-story-icon').addClass('icon-heart').removeClass('icon-heart-empty');
		        }
		    });
		}
		else if($(".icon-heart").length){
			$.ajax({
				url:"/removeFavoriteStory/" + storyId,
				type: "delete", 
				success:function(data) {
		            console.log(data);
		            $('#fav-story-icon').addClass('icon-heart-empty').removeClass('icon-heart');
		        }
		    });
		}
	}); 

	// cereri pt favorite author
	$("#fav-author").click(function(){

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	        }
	    });

		if($(".icon-star-empty").length){
		    $.ajax({
				url:"/addFavoriteAuthor",
				type: "post", 
				data: {
					story_id: storyId
				},
				success:function(data) {
		            console.log(data);
		            $('#fav-author-icon').addClass('icon-star').removeClass('icon-star-empty');
		        }
		    });
		}
		else if($(".icon-star").length){
			$.ajax({
				url:"/removeFavoriteAuthor/" + storyId,
				type: "delete", 
				success:function(data) {
		            console.log(data);
		            $('#fav-author-icon').addClass('icon-star-empty').removeClass('icon-star');
		        }
		    });
		}
	}); 

});