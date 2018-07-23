function toggler(divId) {
  $("#" + divId).toggle();
}

function hasClass(elem, className) {
    return elem.className.split(' ').indexOf(className) > -1;
}

document.addEventListener('click', function (e) {
    if (hasClass(e.target, 'removeevent')) {
      if (confirm("Wollen Sie dieses Ereignis wirklich löschen?")) {
        var eid = e.target.id.substring(5); // $(this) refers to button that was clicked
        var editor = $("#editor").val();
        jQuery.ajax({
          type    : "POST",
          url     : "scripts/removeereignis.php",
          data    : { editor:editor,
                      eid:eid
                    },
          success : function(){
            var row = document.getElementById("erow"+eid);
            row.parentNode.removeChild(row);
          },
          error : function (xhr, ajaxOptions, thrownError){
            //On error, we alert user
            //alert(thrownError);
            var responseText=JSON.parse(xhr.responseText);
            alert(responseText.messages);
          },
        });
      }
    }
}, false);

$(document).ready(function() {
    $("#add").click(function(){
        var element = $(this);
        var etype = $("#ereignistyp").val();
        var kid = $("#kid").val();
        var quelle = $("#quelleid").val();
        var text = $("#text").val();
        var evgtag = $("#evgtag").val();
        var evgzeitraum = $("#evgzeitraum").val();
        var kidfremd1 = $("#kid1id").val();
        var kidfremd2 = $("#kid2id").val();
        var evverbandvid = $("#evverbandvid").val();
        var ktypid = $("#ktypid").val();
        var editor = $("#editor").val();
        jQuery.ajax({
          type    : "POST",
          url     : "scripts/addereignis.php",
          data    : { etype:etype,
                      kid:kid,
                      quelle:quelle,
                      text:text,
                      evgtag:evgtag,
                      evgzeitraum:evgzeitraum,
                      kidfremd1:kidfremd1,
                      kidfremd2:kidfremd2,
                      evverbandvid:evverbandvid,
                      ktypid:ktypid,
                      editor:editor
                    },
          success : function(data){
               var line='<tr id="erow'+data["newid"]+'"><td>';

               if (evgtag!=="" && evgtag!=="0000-00-00") {
                 line+= evgtag;
               } else {
                 line+= evgzeitraum;
               }
               line+= "</td><td>"+$("#ereignistyp :selected").text()+": ";

               var felder = [];

               if (text!=="") {
                 felder[felder.length] = text;
               }

               if (kidfremd1!=="") {
                 felder[felder.length] = "<a href='details.php?kid="+kidfremd1+"'>"+$("#kid1").val()+"</a>";
               }

               if (kidfremd2!=="") {
                 felder[felder.length] = "<a href='details.php?kid="+kidfremd2+"'>"+$("#kid2").val()+"</a>";
               }

               if (evverbandvid!=="") {
                 felder[felder.length] = $("#evverband").val();
               }

               if (ktypid!=="") {
                 felder[felder.length] = $("#ktyp").val();
               }

               line += felder.join(", ");

               if (quelle!=="") {
                 line+= "</td><td><a href='quellen.php#anker"+quelle+"'>"+$("#quelle").val()+"</a></td>";
               } else {
                 line+= "</td><td></td>";
               }

               line += '<td><button type="button" class="btn btn-danger btn-sm removeevent" id="rmbtn'+data["newid"]+'"><span class="glyphicon glyphicon-trash"></button></td>';

               line += "</tr>";

               var tbody = $('#ereignisse > tbody:last');

			   tbody.append(line);

			   var rows = $('tr', tbody);
			   rows.sort(function(a, b) {
				 var keyA = $('td', a)[0].innerHTML;
				 var keyB = $('td', b)[0].innerHTML;

				 return keyA.localeCompare(keyB);
			   });

			   rows.each(function(index, row) {
				 tbody.append(row);
			   });
             },
          error : function (xhr, ajaxOptions, thrownError){
               //On error, we alert user
               //alert(thrownError);
               var responseText=JSON.parse(xhr.responseText);
               alert(responseText.messages);
             },
          });

        return false;
    });
});
