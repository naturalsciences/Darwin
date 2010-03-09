$(document).ready(function () {

  $("select.catalogue_level").change(function(){
   if ($(this).val() == '')
   {
     $('tr#parent_ref').fadeOut();
   }
   else
   {
     $('tr#parent_ref').fadeIn();
     if ($('a#searchPUL').attr('href').length)
     {
       $.ajax({
               url: $('a#searchPUL').attr('href')+'?level_id='+$(this).val()+'&parent_id='+$('input[id$=\"_parent_ref\"]').val()+'&table='+$('input[id$=\"_table\"]').val(),
               success: function(html) 
               {
                 switch (html)
                 {
                   case "top":
                     $('div[id$=\"_parent_ref_button\"], div[id$=\"_parent_ref_warning\"]').fadeOut();
                     $('input[id$=\"_parent_ref\"]').val("0");
                     $('div[id$=\"_parent_ref_name\"]').html("-");
                     break;
                   case "not ok":
                     $('div[id$=\"_parent_ref_button\"], div[id$=\"_parent_ref_warning\"]').fadeIn();
                     break;
                   default:
                     $('div[id$=\"_parent_ref_button\"]').fadeIn();
                     $('div[id$=\"_parent_ref_warning\"]').fadeOut();
                     break;
                 }
               }
              });
     }
   }
   return false;
 });

});