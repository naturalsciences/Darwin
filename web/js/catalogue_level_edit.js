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

 $('tr#parent_ref input[type="hidden"]').bind('loadref',function()
  {
    ref_level_id = $('select[id$=\"_level_ref\"]').val();
    if (ref_level_id.length)
    {
      ref_level_id = '/level/'+ref_level_id;
    }
    ref_caller_id = $('input[id$=\"_id\"]').val();
    if (ref_caller_id.length)
    {
      ref_caller_id = '/caller_id/'+ref_caller_id;
    }

    button = $(this).parent().find('.but_text');

    if(button.data('href') == null)
    {
      button.data('href', button.attr('href'));
    }
    
    button.attr('href', button.data('href') + ref_level_id + ref_caller_id);

  });

 $('tr#parent_ref input[type="hidden"]').change(function()
 {
    $.ajax({
      url: $('a#searchPUL').attr('href')+ '/' + $('select[id$=\"_level_ref\"]').val() + '/parent_id/' + $(this).val() + '/table/' +$('input[id$=\"_table\"]').val(),
      success: function(html) 
      {
	if (html == 'ok')
	{
	  $('div[id$=\"_warning\"]').hide();
	}
      }
    });
 });

});