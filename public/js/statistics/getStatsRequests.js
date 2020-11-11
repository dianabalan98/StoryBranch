
jQuery(document).ready(function($){

	getCommentsPerStory()
	// cerere GET pt comentariile fiecarei povesti a autorului
	$("#byCommentsStoryBtn").click(function(){

		getCommentsPerStory()
	});

	function getCommentsPerStory() {
		$("#selectFragment").css('visibility', 'hidden')

		$.ajax({
			url:"getCommentsPerStory",
			type: "get", 
			success:function(data) {
	       
	            var results = JSON.parse(data)
	            console.log(results)
	            var labels = results.labels
	            var comments = results.comments            
	            var rgbArray = randomColors(comments.length)

	            addCanvas(600, 400)

	            makeGraphic(labels, comments, rgbArray, 'bar', 'Comments per story')
	        }
	    });
	}


	// cerere GET pt scorul de favorite per fiecare poveste a autorului
	$("#byFavoritesStoryBtn").click(function(){

		$("#selectFragment").css('visibility', 'hidden')

		$.ajax({
			url:"getFavoritesPerStory",
			type: "get", 
			success:function(data) {
	       
	            var results = JSON.parse(data)
	            console.log(results)
	            var labels = results.labels
	            var favs = results.favs
	            var rgbArray = randomColors(favs.length)

	            addCanvas(600, 400)

	            makeGraphic(labels, favs, rgbArray, 'bar', 'Favorites per story')	            
	            
	        }
	    });
	});


	// cerere GET pt primire dinamica a povestilor in dropdown =>  dupa incarcarea paginii
	$.ajax({
		url:"/getStoriesForStats",
		type: "get", 
		success:function(data) {
       
            console.log(data)
            $.each(data, function(index, story) {
            	option = ''

	        	if(story.title.length > 30) {
			   		shorter = story.title.substring(0, 30);
			   		shorter += '...';
			   		option = "<option class='storyOption' value='"+story.id+"'>"+shorter+"</option>"
			   	}
			   	else {
			   		option = "<option class='storyOption' value='"+story.id+"'>"+story.title+"</option>"
			   	}

			   $("#selectStory").append(option)
			});            
            
        }
    });


    // cerere GET pt primirea nr de favs pt autor (de cate ori a fost adaugat la favorite de alti cititori)
    $.ajax({
		url:"/getFavoritesPerAuthor",
		type: "get", 
		success:function(data) {

            $("<div id='favs'><div id='favsAuthor'>"+data+"</div><br><p class='favText'>Added to favorites!</p></div>").insertBefore( "#comFrag" );             
            
        }
    });


    // cerere GET pt afisare grafic comentarii per fragmente poveste
    $("select[name='selectStory[]']").change(function(){

   		story_id = $(this).val()

   		$.ajax({
			url:"/getCommentsPerFragments/" + story_id,
			type: "get", 
			success:function(data) {
	       
	            console.log(data)
	            getFragmentsForStats(story_id)

	            var results = JSON.parse(data)
	            var labels = results.labels
	            var comments = results.comments            
	            var rgbArray = randomColors(comments.length)

	            addCanvas(550, 400)

	            makeGraphic(labels, comments, rgbArray, 'doughnut', 'Comments per fragment of selected story')
	            
	        }
	    });

  	});

    // functie pentru aducerea dinamica a nodurilor in dropdown
  	function getFragmentsForStats(story_id) {

  		$(".nodeOption").remove()

  		$.ajax({
			url:"/getNodesForStats/" + story_id,
			type: "get", 
			success:function(data) {

				if(data) {
	            	$.each(data, function(index, node) {
		            	option = ''

			        	if(node.subtitle.length > 30) {
					   		shorter = node.subtitle.substring(0, 30);
					   		shorter += '...';
					   		option = "<option class='nodeOption' value='"+node.id+"'>"+shorter+"</option>"
					   	}
					   	else {
					   		option = "<option class='nodeOption' value='"+node.id+"'>"+node.subtitle+"</option>"
					   	}

					   $("#selectFragment").css('visibility', 'visible')
					   $("#selectFragment").append(option)
					});
	            }
			}
		})  		
  	}


  	// cerere GET pt afisare grafic emotii per fragment
    $("select[name='selectFragment[]']").change(function(){

   		node_id = $(this).val()

   		$.ajax({
			url:"/getEmotionsPerFragment/" + node_id,
			type: "get", 
			success:function(data) {
	       
	            console.log(data)
	      
	            var results = JSON.parse(data)
	            var labels = results.labels
	            var emotions = results.emotions            
	            var rgbArray = randomColors(emotions.length)

	            addCanvas(500, 380)

	            makeGraphic(labels, emotions, rgbArray, 'pie', 'Emotions per fragment of selected story')
	            
	        }
	    });

  	});
	
});