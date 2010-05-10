jQuery(function () 
  {
    $('form.qtiped_form').submit(function () {
      $('form.qtiped_form input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('form.qtiped_form').parent());
      $.ajax({
          type: "POST",
          url: $(this).attr('action'),
          data: $(this).serialize(),
          success: function(html){
            if(html == 'ok')
            {
              $('.qtip-button').click();
            }
            $('form.qtiped_form').parent().before(html).remove();
          }
      });
      return false;
    });
  });
