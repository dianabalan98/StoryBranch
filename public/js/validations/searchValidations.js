// pt search

jQuery(document).ready(function($){

	$("#searchInput").keypress(function(e){
		var key = e.which;
		if(key == 13)  // the enter key code
		{
			checkInput()
		}
	});

	$("#searchBtn").click(function(){
		checkInput()
	});

	function checkInput() {
		var input = $("#searchInput").val()

		if(input.length > 100) {
			alert("Whoa there, you want to search quite a lot! ;)")
			$("#searchBtn").prop('disabled', true)
		}
		else {
			$("#searchBtn").prop('disabled', false)
		}
	}
});