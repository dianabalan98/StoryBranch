jQuery(document).ready(function($){

	var authorId = $("#author_id").val();
	var stories;

	$.ajax({
		url:"/getAllPublishedStories/" + authorId,
		type: "get", 
		success:function(data) {

			console.log(data)
			stories = data;
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
		}
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