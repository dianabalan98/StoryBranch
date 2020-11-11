// pt relatii noduri

jQuery(document).ready(function($){

	$("#btn-save").prop('disabled', true); 

	$(".relMember").mouseleave(function(){	

		var parentId = $('#parentId').val();
	    var childId = $('#childId').val();

		
	    if(parentId == childId) {
	    	if($("#errorParentChild").length == 0) {
	    		error = "<p id='errorParentChild' class='errorMessage'>Parent and child must be different!</p>"
	    		$("#divChildId").append(error)
	    	}
	    }
	    else $("#errorParentChild").remove()   
	    checkSubmit()
	}); 

	$("#choice").mouseleave(function(){

		var choice = $('#choice').val();
		var parentId = $('#parentId').val();
	    var childId = $('#childId').val();

	    if(choice == ''){
	    	if($("#errorChoice").length == 0) {
	    		error = "<p id='errorChoice' class='errorMessage'>Choice is required!</p>"
			    $("#divChoice").append(error)
	    	}
	    	else $("#errorChoice").text("Choice is required!")
	    }
	    else if(choice.length > 200){
	    	if($("#errorChoice").length == 0) {
	    		error = "<p id='errorChoice' class='errorMessage'>Choice is longer than 200 characters!</p>"
			    $("#divChoice").append(error)
	    	}
	    	else $("#errorChoice").text("Choice is longer than 200 characters!")
	    }
		else if(choice.length < 200) {
			$("#errorChoice").remove()
		}

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
			url:"/checkDuplicateChoice",
			type: "post", 
			data: {
				parentId: parentId,
				choice: choice,
				childId: childId
			},
			success:function(data) {
	            
	     		if(data) {
	     			if($("#errorDuplicateChoice").length == 0) {
			    		error = "<p id='errorDuplicateChoice' class='errorMessage'>"+data+"</p>"
			    		$("#divChoice").append(error)
			    	}
	     		}
	     		else $("#errorDuplicateChoice").remove()
	     		checkSubmit()
	        }
	    });
	});


	//daca sunt erori in form cand dam submit, se va bloca trimiterea formularului
    //altfel va fi permisa
    function checkSubmit() {
        if($(".errorMessage").length == 0) {
            $("#btn-save").prop('disabled', false);      
        }
        else $("#btn-save").prop('disabled', true);      
    }

});