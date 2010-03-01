$(document).ready(function () {

  $('.reference_clear').live('click',function()
  {
    $(this).prevAll('input').val('');
    $(this).hide();
  });
});