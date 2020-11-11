jQuery(document).ready(function($){

	// PRIMIRE DINAMICA A CATEGORIILOR IN DROPDOWN
	$.ajax({
		url:"/getCategories",
		type: "get", 
		success:function(data) {
       		
       		results = JSON.parse(data)
            $.each(results, function(index, categ) {
            	link = ''
			   	link = "<a class='dropdown-item' href='http://localhost:8000/getStoryByCategory/"+categ.id+"'>"+categ.name+"</a>"

			   $("#categoriesLinks").append(link)
			});            
            
        }
	})

})

