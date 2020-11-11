// pt comment

jQuery(document).ready(function($){
	$("#postCommentBtn").prop('disabled', true)

	$("#commentBody").mouseleave(function(){
		checkInput()
	});

	function checkInput() {
		var input = $("#commentBody").val()

		if(input.length > 1000) {
			if($("#errorComment").length == 0) {
				errorMsg = "<p id='errorComment' class='errorMessage'>Comment longer than 1000 characters!</p>"
                $("#addComment").append(errorMsg)
			}
			
			$("#postCommentBtn").prop('disabled', true)

		}
		else if(input.length < 1000 && input.length > 0){
			$("#errorComment").remove()
			$("#postCommentBtn").prop('disabled', false)
		}
	}
});