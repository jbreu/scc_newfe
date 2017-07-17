$(document).ready(function() {
  $('#ort').autocomplete({
  		      	source: function( request, response ) {
  		      		$.ajax({
  		      			url : 'scripts/autocomplete.php',
  		      			dataType: "json",
  						data: {
  						   name_startsWith: request.term,
  						   type: 'ort'
  						},
  						 success: function( data ) {
  							 response( $.map( data, function( item ) {
  								 return {
						          label: item.label,
						          value: item.value,
  								}
  							}));
  						}
  		      		});
  		      	},
              select: function (event, ui) {
                 $('#ort').val(ui.item.label); // display the selected text
                 $("#ortid").val(ui.item.value);
                 return false;
             },
  		      	autoFocus: true,
  		      	minLength: 2
  		      });

            $('#nachfolger').autocomplete({
            		      	source: function( request, response ) {
            		      		$.ajax({
            		      			url : 'scripts/autocomplete.php',
            		      			dataType: "json",
            						data: {
            						   name_startsWith: request.term,
            						   type: 'nachfolger'
            						},
            						 success: function( data ) {
            							 response( $.map( data, function( item ) {
            								 return {
          						          label: item.label,
          						          value: item.value,
            								}
            							}));
            						}
            		      		});
            		      	},
                        select: function (event, ui) {
                           $('#nachfolger').val(ui.item.label); // display the selected text
                           $("#nachfolgerid").val(ui.item.value);
                           return false;
                       },
            		      	autoFocus: true,
            		      	minLength: 2
            		      });
});
