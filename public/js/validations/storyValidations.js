// pt creare si editare story

jQuery(document).ready(function($){
    $("#storySubmitBtn").prop('disabled', true)

    $("#title").mouseleave(function() {
        var titleRegex = /^[A-Za-z0-9'.\s_\-\,\?\!]+$/
        var titleInput = $("#title").val()
        var error = null

        if($("#title").val() == ""){
            error = "Title is required!" 
        }
        else if($("#title").val().length > 100){
            error = "Title is longer than 100 characters!"
        }
        else if(!titleRegex.test(titleInput)) {
            error = "Title can only contain letters, numbers, space, dots, dashes and underscores!"
        }
        else if($("#title").val().length < 100 && $("#title").val().length > 0) {
            $("#errorTitle").remove()
        }

        if(error){
            if ($("#errorTitle").length == 0) {
                errorMsg = "<p id='errorTitle' class='errorMessage'>"+error+"</p>"
                $("#divTitle").append(errorMsg)
            } 
            else  $("#errorTitle").text(error)      
        }

        checkSubmit()
    });

    $("#description").mouseleave(function() {
        if($("#description").val() == "" && $("#errorDescription").length === 0){
            error = "<p id='errorDescription' class='errorMessage'>Description is required!</p>"
            $("#divDescription").append(error)
        }
        else {
            if($("#description").val().length > 1000){
                $("#errorDescription").text("Description is longer than 1000 characters!")
            }
            else if($("#description").val().length < 1000 && $("#description").val().length > 0) {
                $("#errorDescription").remove()
            }
        }

        checkSubmit()
    });

    $("#tags").on("input", function(e) {   
        var tagsInput = $("#tags").val()
        var regex = /^([a-z0-9]*[\s]?)*$/ 

        if(!regex.test(tagsInput)){

           if($("#errorTags").length === 0) {
                error = "<p id='errorTags' class='errorMessage'>Only lowercase letters, numbers and spaces are allowed!</p>"
                $("#divTags").append(error)
            }
        }
        else $("#errorTags").remove()

        checkSubmit()
    });


    $("#cover").on("change", function() {
        var fileInput = $("#cover").val()
        var fileExt = fileInput.split('.').pop()
        
        if (fileExt != "jpg" && fileExt != "jpeg" && fileExt != "png") {
            if($("#errorCover").length === 0) {
                error = "<p id='errorCover' class='errorMessage'>Only jpg, jpeg and png formats are allowed!</p>"
                $("#divCover").append(error)
            }
            $("#cover").empty()
        }
        else {
            $("#errorCover").remove()
        }

        checkSubmit()
    });

    //daca sunt erori in form cand dam submit, se va bloca trimiterea formularului
    //altfel va fi permisa
    function checkSubmit() {
        if($(".errorMessage").length == 0) {
            $("#storySubmitBtn").prop('disabled', false);      
        }
        else $("#storySubmitBtn").prop('disabled', true);      
    }
    
   
});