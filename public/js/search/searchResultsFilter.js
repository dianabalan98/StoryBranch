jQuery(document).ready(function($){

	// PRIMIRE DINAMICA A CATEGORIILOR IN DROPDOWN
	$.ajax({
		url:"/getCategories",
		type: "get", 
		success:function(data) {
       		
       		results = JSON.parse(data)
            $.each(results, function(index, categ) {
            	option = ''
			   	option = "<option class='categoryOption' value='"+categ.name+"'>"+categ.name+"</option>"

			   $("#categoryFilter").append(option)
			});            
            
        }
	})
	
	$(document).on("change", "select[name='categoryFilter[]']", function(){
		
		$(".storyHolderMain").show()
			categ_id = $(this).val()
			
			// iterare prin toate categoriile afisate 
			// iar cele care nu se potrivesc cu cea selectata vor avea div-ul parinte ascuns
			$(".storyCategory").each(function() {

				if($(this).text() != categ_id) {
					$(this).parent().parent().hide()
				}
		   
		});
		
	})
})