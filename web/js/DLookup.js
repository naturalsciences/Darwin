var ref_element_id = null;
var ref_element_name = null;
$(document).ready(function () {

  $('.reference_clear').live('click',function()
  {
//    $(this).prevAll('.ref_name').text('');
    $(this).prevAll('input').val('');
//    $(this).next().find('.but_text').text('Choose !');
    $(this).hide();
  });
});