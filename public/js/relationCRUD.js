// CREATE function - creare relatie intre noduri
// cand se apasa butonul cu id = btn-add 
jQuery(document).ready(function($){
    ////----- Open the modal to CREATE a relation -----////
    jQuery('#btn-add').click(function () {
        jQuery('#btn-save').val("add");
        jQuery('#modalFormData').trigger("reset");
        jQuery('#relationEditorModal').modal('show');
    });
 
    ////----- Open the modal to UPDATE a relation -----////
    jQuery('body').on('click', '.open-modal', function () {
        var relation_id = $(this).val();    //id itemul selectat din pagina
        $.get('/story/relations/edit/' + relation_id, function (data) {
            jQuery('#relation_id').val(data.id);
            jQuery('#parentId').val(data.parent_id);
            jQuery('#choice').val(data.choice);
            jQuery('#childId').val(data.child_id);
            jQuery('#btn-save').val("update");          // daca vreau sa updatez atunci valoarea butonului de save primeste update
            jQuery('#relationEditorModal').modal('show');
        })
    });
 
    // Clicking the save button on the open modal for both CREATE and UPDATE
    $("#btn-save").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            parentId: jQuery('#parentId').val(),
            choice: jQuery('#choice').val(),
            childId: jQuery('#childId').val()
        };
        var state = jQuery('#btn-save').val();
        var type = "post";
        var relation_id = jQuery('#relation_id').val();
        var ajaxurl = '/story/relations/create';
        if (state == "update") {
            type = "put";
            ajaxurl = '/story/relations/update/' + relation_id;
            
        }
        console.log('Sending data to: ' + ajaxurl);

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            //dataType: 'application/json',
            success: function (data) {
            	console.log(data);
            	//data = JSON.parse(data.responseText);            	
            	
                var relation = '<tr id="rel' + data.relation.id + '"><td>' + data.parent_subtitle + '</td><td>' + data.relation.choice + '</td><td>' + data.child_subtitle + '</td>';
                relation += '<td><button class="btn btn-warning open-modal icon-edit btnRelation" value="' + data.relation.id + '"></button>&nbsp;';
                relation += '<button class="delete-modal btn btn-danger delete-link btnRelation" value="' + data.relation.id + '"><b>X</b></button></td></tr>';
                if (state == "add") {
                    jQuery('#relations-list').append(relation);
                    window.location.reload();
                } else {
                    $("#rel" + relation_id).replaceWith(relation);
                    window.location.reload();
                }
                jQuery('#modalFormData').trigger("reset");
                jQuery('#relationEditorModal').modal('hide')
            },
            error: function (data) {
            	console.log('Error:', data);
            }
        });
    });
 
    ////----- DELETE a relation and remove from the page -----////
    jQuery('.delete-relation').click(function () {
        var relation_id = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "DELETE",
            url: '/story/relations/delete/' + relation_id,
            success: function (data) {
                console.log(data);
                $("#rel" + relation_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
});



