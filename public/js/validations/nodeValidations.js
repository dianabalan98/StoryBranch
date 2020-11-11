// pt creare si editare node

jQuery(document).ready(function($){
    $("#nodeSubmitBtn").prop('disabled', true); 
    var regex = /^[A-Za-z0-9'.\s_\-\,\?\!]+$/    

    $("#subtitle").mouseleave(function() {
         var subtitleInput = $("#subtitle").val()
         var error = null

        if($("#subtitle").val() == ""){
            error = "Subtitle is required!"                 
        }
        else if($("#subtitle").val().length > 100){
            error = "Subtitle is longer than 100 characters!"
        }
        else if(!regex.test(subtitleInput)) {
            error = "Subtitle can only contain letters, numbers, space, dots, dashes and underscores!"
        }
        else if($("#subtitle").val().length < 100 && $("#subtitle").val().length > 0) {
            $("#errorSubtitleLen").remove()
        }
        

        if(error){
            if ($("#errorSubtitleLen").length == 0) {
                errorMsg = "<p id='errorSubtitleLen' class='errorMessage'>"+error+"</p>"
                $("#divSubtitle").append(errorMsg)
            } 
            else  $("#errorSubtitleLen").text(error)      
        }

        checkSubmit()
    });

    $("#body").mouseleave(function() {
        if($("#body").val() == "" && $("#errorBody").length == 0){
            error = "<p id='errorBody' class='errorMessage'>Body is required!</p>"
            $("#divBody").append(error)
        }
        else {
            if($("#body").val().length > 200000){
                $("#errorBody").text("Body is longer than 200000 characters!")
            }
            else if($("#body").val().length < 200000 && $("#body").val().length > 0) {
                $("#errorBody").remove()
            }
        }

        checkSubmit()
    });

    //daca sunt erori in form cand dam submit, se va bloca trimiterea formularului
    //altfel va fi permisa
    function checkSubmit() {
        if($(".errorMessage").length == 0) {
            $("#nodeSubmitBtn").prop('disabled', false);      
        }
        else $("#nodeSubmitBtn").prop('disabled', true);      
    }
    
   
});