jQuery(document).ready(function($){

	var nodeId = $("#nodeId").val();	
	var storyId = $("#storyId").val();

	$("#postCommentBtn").click(function(){	
		var comment_body = $("#commentBody").val();

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
			url:"/addComment",
			type: "post", 
			data: {
				story_id: storyId,
				node_id: nodeId,
				comment_body: comment_body
			},
			success:function(data) {
	            console.log(data);
	            $("#commentBody").val('');
	            getComments();
	        }
	    });
	}); 

	if($('#comments').length){
		getComments();
	}

	function getComments() {
		$.ajax({
			url:"/getComments/" + nodeId,
			type: "get", 
			success:function(data) {
	            //console.log(data);
	            $.each(data , function(index, comment) {
	            	
	            	comment_content = "<div class='comment' id='comment"+comment.id+"'>" +
		            					  "<div class='user-img'><img src='/uploads/user/"+comment.avatar+"' alt='"+comment.avatar+"' class='avatar' style='width:70px;height:70px;'></div>" +
		            					  "<div class='comment-body'><a href='http://localhost:8000/userProfile/"+comment.user_id+"''><b>"+comment.username+"</b></a>" +
		            					  	"<p class='comment-date'>"+comment.created_at+"</p><br>" +
		            					  	"<p>"+comment.body+"</p></div>"
	            					  "</div><br>"
	            	if($('#comment'+comment.id).length < 1) {
	            		$('#comments').append($(comment_content));
	            	}	

	            });
	        }
	    });
	}

});