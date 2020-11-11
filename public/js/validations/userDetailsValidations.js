// pt user details

jQuery(document).ready(function($){

	// USERNAME
	$("#username").mouseleave(function() {
         var username = $("#username").val()
         var error = null

        if($("#username").val() == ""){
            error = "Username is required!"                 
        }
        else if($("#username").val().length > 100){
            error = "Username is longer than 100 characters!"
        }
        else if($("#username").val().length < 100 && $("#username").val().length > 0) {
            $("#errorUsername").remove()
        }
        

        if(error){
            if ($("#errorUsername").length == 0) {
                errorMsg = "<p id='errorUsername' class='errorMessage'>"+error+"</p>"
                $("#divUsername").append(errorMsg)
            } 
            else  $("#errorUsername").text(error)      
        }

    	$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
			url:"/checkUsername",
			type: "post", 
			data: {
				username: username
			},
			success:function(data) {
	            
	     		if(data) {
	     			if($("#errorDuplicateUsername").length == 0) {
			    		error = "<p id='errorDuplicateUsername' class='errorMessage'>Username already taken!</p>"
			    		$("#divUsername").append(error)
			    	}
	     		}
	     		else $("#errorDuplicateUsername").remove()
	     		checkSubmit()
	        }
	    });

        checkSubmit()
    });


	// EMAIL
    $("#email").mouseleave(function() {
    	var email = $("#email").val()
        var error = null

        if($("#email").val() == ""){
            error = "Email is required!"                 
        }
        else if($("#email").val().length > 255){
            error = "Email is longer than 255 characters!"
        }
        else if($("#email").val().length < 255 && $("#email").val().length > 0) {
            $("#errorEmail").remove()
        }
        

        if(error){
            if ($("#erroremail").length == 0) {
                errorMsg = "<p id='errorEmail' class='errorMessage'>"+error+"</p>"
                $("#divEmail").append(errorMsg)
            } 
            else  $("#errorEmail").text(error)      
        }

    	$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
			url:"/checkEmail",
			type: "post", 
			data: {
				email: email
			},
			success:function(data) {
	            
	     		if(data) {
	     			if($("#errorDuplicateEmail").length == 0) {
			    		error = "<p id='errorDuplicateEmail' class='errorMessage'>Email already taken!</p>"
			    		$("#divEmail").append(error)
			    	}
	     		}
	     		else $("#errorDuplicateEmail").remove()
	     		checkSubmit()
	        }
	    });

        checkSubmit()
    });

    // BIO
    $("#bio").mouseleave(function() {
    	var bio = $("#bio").val()
        var error = null

        if($("#bio").val().length > 700){
            error = "Bio is longer than 700 characters!"
        }
        else if($("#bio").val().length < 700) {
            $("#errorBio").remove()
        }
        

        if(error){
            if ($("#errorBio").length == 0) {
                errorMsg = "<p id='errorBio' class='errorMessage'>"+error+"</p>"
                $("#divBio").append(errorMsg)
            } 
            else  $("#errorBio").text(error)      
        }

    	checkSubmit()
    });

    // AVATAR
    $("#avatar").on("change", function() {
        var fileInput = $("#avatar").val()
        var fileExt = fileInput.split('.').pop()
        
        if (fileExt != "jpg" && fileExt != "jpeg" && fileExt != "png") {
            if($("#errorAvatar").length === 0) {
                error = "<p id='errorAvatar' class='errorMessage'>Only jpg, jpeg and png formats are allowed!</p>"
                $("#divAvatar").append(error)
            }
            $("#avatar").empty()
        }
        else {
            $("#errorAvatar").remove()
        }

        checkSubmit()
    });


    //daca sunt erori in form cand dam submit, se va bloca trimiterea formularului
    //altfel va fi permisa
    function checkSubmit() {
        if($(".errorMessage").length == 0) {
            $("#updateUserBtn").prop('disabled', false);      
        }
        else $("#updateUserBtn").prop('disabled', true);      
    }

});