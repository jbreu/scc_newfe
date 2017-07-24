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

                      $('#kid1').autocomplete({
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
                                     $('#kid1').val(ui.item.label); // display the selected text
                                     $("#kid1id").val(ui.item.value);
                                     return false;
                                 },
                      		      	autoFocus: true,
                      		      	minLength: 2
                      		      });

                        $('#kid2').autocomplete({
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
                                         $('#kid2').val(ui.item.label); // display the selected text
                                         $("#kid2id").val(ui.item.value);
                                         return false;
                                     },
                          		      	autoFocus: true,
                          		      	minLength: 2
                          		      });

                      $('#verband').autocomplete({
                      		      	source: function( request, response ) {
                      		      		$.ajax({
                      		      			url : 'scripts/autocomplete.php',
                      		      			dataType: "json",
                          						data: {
                          						   name_startsWith: request.term,
                          						   type: 'verband'
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
                                     $('#verband').val(ui.item.label); // display the selected text
                                     $("#verbandid").val(ui.item.value);
                                     return false;
                                 },
                      		      	autoFocus: true,
                      		      	minLength: 2
                      		      });

                    $('#evverband').autocomplete({
                    		      	source: function( request, response ) {
                    		      		$.ajax({
                    		      			url : 'scripts/autocomplete.php',
                    		      			dataType: "json",
                        						data: {
                        						   name_startsWith: request.term,
                        						   type: 'verband'
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
                                   $('#evverband').val(ui.item.label); // display the selected text
                                   $("#evverbandvid").val(ui.item.value);
                                   return false;
                               },
                    		      	autoFocus: true,
                    		      	minLength: 2
                    		      });

                    $('#ktyp').autocomplete({
                                source: function( request, response ) {
                                  $.ajax({
                                    url : 'scripts/autocomplete.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       type: 'korporationstyp'
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
                                   $('#korporationstyp').val(ui.item.label); // display the selected text
                                   $("#korporationstypid").val(ui.item.value);
                                   return false;
                               },
                                autoFocus: true,
                                minLength: 2
                              });

                    $('#quelle').autocomplete({
                                source: function( request, response ) {
                                  $.ajax({
                                    url : 'scripts/autocomplete.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       type: 'quelle'
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
                                   $('#quelle').val(ui.item.label); // display the selected text
                                   $("#quelleid").val(ui.item.value);
                                   return false;
                                },
                                autoFocus: true,
                                minLength: 2
                              });
});
