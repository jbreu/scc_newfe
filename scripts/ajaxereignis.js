function toggler(divId) {
  $("#" + divId).toggle();
}

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
                      ktypid:ktypid
                    },
          success : function(){
               var line="<tr><td>";

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
                 line+= "</td><td><a href='quellen.php#anker"+quelle+"'>"+$("#quelle").val()+"</a></td></tr>";
               } else {
                 line+= "</td><td></td></tr>";
               }

               $('#ereignisse > tbody:last').append(line);
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
