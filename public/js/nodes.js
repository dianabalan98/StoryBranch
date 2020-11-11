jQuery(document).ready(function($){
	// atunci cand am un nod root, e obligatoriu sa i se poata afisa subtitlul 
	// cazul cand poate am mai multe noduri root si afisez subtitlurile ca alegeri posibile de citire pt cititor

    $("#root").click(function() {
        $("#displaySubtitle").prop("checked", true);

        if ($("#root").is(':checked')) {

        	$("#displaySubtitle").prop("disabled", true);
        }
        else {
        	$("#displaySubtitle").prop("disabled", false);
        }
        
    });
});