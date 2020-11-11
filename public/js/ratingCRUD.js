original_check = false;
jQuery(document).ready(function($){
	
	var nodeId = $("#nodeId").val();

	function setCallback(new_value){
	    original_check = new_value;
	    console.log("Callback: " + original_check);
	    displayValue();
	}

	function displayValue() {
		console.log(original_check);
	}

	// daca exista un rating dat de cititor pt acest nod, il aducem in pagina
	$.ajax({
		url:"/getRating",
		type: "get",
		data: {
			node_id: nodeId
		},
		success:function(data){
	        	
	        	if(data != "error") {
	        		$("input[name='emotion'][value='" + data + "']").prop("checked",true);
	        		setCallback(true);
	        		console.log(data);
	        		checkIfEndOfPath();
	        	}
	        	else {
	        		console.log(data);
	        	}
			
		}
	});

	$("input[name='emotion']").change(function() {
		// daca cititorul a dat deja un rating pt nodul curent
		if(original_check == true) { 
			var ratingValue = $("input[name='emotion']:checked").val();
			var nodeId = $("#nodeId").val();
			var formData = {
    			emotion: ratingValue,
    			node_id: nodeId,
    		};

			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	            }
	        });

	        $.ajax({
        		url:"/editRating/",
        		type: "put",
        		data: formData, 
        		success:function(data){
       	        	console.log("edit function");		
        			console.log(data);
        			checkIfEndOfPath();
        		}
        	});
        }

        // daca cititorul nu a dat niciodata un rating pt nodul curent
        else if (original_check == false) {
        	
			var ratingValue = $("input[name='emotion']:checked").val();
			var nodeId = $("#nodeId").val();
			var storyId = $("#storyId").val();
			var formData = {
    			emotion: ratingValue,
    			node_id: nodeId,
    			story_id: storyId
    		};

			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	            }
	        });

	        $.ajax({
        		url:"/story/rating/" + storyId + "/" + nodeId + "/" + ratingValue,
        		type: "post",
        		data: formData, 
        		success:function(data){
       	        	console.log("post function");
        			console.log(data);
        			checkIfEndOfPath();
        		}
        	});				
        }
	});


	// aceasta functie verifica daca am ajuns la un capat de fir narativ
	// atunci cand nu avem afisate alegeri noduri root, sau orice alt tip de alegeri
	function checkIfEndOfPath() {
		if($('a.rootNodes').length == 0 && $('a.choice').length == 0) {
			//alert('End of path');
			//adaug path-ul in baza de date, acesta exista in var de sesiune history
			// cu jquery trimit doar story_id
			var storyId = $("#storyId").val();

			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
	            }
	        });

	        $.ajax({
        		url:"/story/addPath/" + storyId,
        		type: "post",
        		data: storyId,
        		success:function(data){

        			alert(data);
        		}
        	});	
		}
	}
	
});