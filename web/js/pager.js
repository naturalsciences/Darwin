jQuery(function () 
       {
         $(".rec_per_page").live('change', function ()
         {
           $.ajax({
                   type: "POST",
                   url: $(this).closest('form').attr('action'),
                   data: $(this).closest('form').serialize(),
                   success: function(html){
                                           $(".search_results_content").html(html);
                                           $('.search_results').slideDown();
                                          }
                  });
           $(".search_results_content").html('<img src="/images/loader.gif" />');
           return false;
         });
       });