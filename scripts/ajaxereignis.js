$(document).ready(function() {
    $("#add").click(function(){
        var element = $(this);
        var text = $("#text").val();
        var kid = $("#kid").val();
        jQuery.ajax({
          type    : "POST",
          url     : "scripts/addereignis.php",
          data    : { text:text, kid:kid },
          success : function(){
               $('#ereignisse > tbody:last').append('<tr><td>'+text+'</td></tr>');
             },
          error:function (xhr, ajaxOptions, thrownError){
               //On error, we alert user
               alert(thrownError);
             },
          });

        return false;
    });
});
